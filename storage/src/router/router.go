package router

import (
	"bytes"
	"encoding/json"
	"fmt"
	"image"
	"image/gif"
	"io"
	"net/http"
	"os"
	"os/exec"
	"path/filepath"
	"storage/pkg/disk"
	"storage/pkg/util"

	"github.com/go-chi/chi/v5"
)

var (
	Sizes            = []int{0, 400, 200, 100}
	MaxDimensionSize = 12000
	StoragePath      = "/storage"
)

func Setup() *chi.Mux {

	r := chi.NewRouter()

	r.Get("/stats", stats)
	r.Post("/file", upload)
	r.Get("/*", getFile)
	r.Delete("/*", deleteFile)

	return r
}

func stats(w http.ResponseWriter, r *http.Request) {

	json.NewEncoder(w).Encode(
		map[string]any{
			"ok": true,
			"self": map[string]any{
				"disk": map[string]uint64{
					"available": disk.State().Available,
					"free":      disk.State().Free,
					"total":     disk.State().Total,
				},
			},
		})

}

func upload(w http.ResponseWriter, r *http.Request) {

	f, _, _ := r.FormFile("file")
	defer f.Close()

	buf, _ := io.ReadAll(f)
	mime := http.DetectContentType(buf)

	isImage := mime == "image/jpeg" || mime == "image/png"
	isVideo := mime == "video/mp4" || mime == "video/webm"
	isGif := mime == "image/gif"

	name := util.MakeFilename()

	var result map[int]map[string]any

	switch {

	case isImage:

		img, format, _ := image.Decode(bytes.NewReader(buf))

		r := img.Bounds()

		if r.Dx() > MaxDimensionSize || r.Dy() > MaxDimensionSize {

			json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": fmt.Sprintf("Слишком большой размер, макс.: %d", MaxDimensionSize)})
			return
		}
		result = processImage(img, name, format)

	case isGif:

		gif, _ := gif.DecodeAll(bytes.NewReader(buf))

		r := gif.Image[0].Bounds()

		if r.Dx() > MaxDimensionSize || r.Dy() > MaxDimensionSize {

			json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": fmt.Sprintf("Слишком большой размер, макс.: %d", MaxDimensionSize)})
			return
		}
		result = processGif(gif, name)

	case isVideo:

		var format string

		switch mime {
		case "video/webm":
			format = "webm"
		case "video/mp4":
			format = "mp4"
		}

		tmp, _ := os.CreateTemp("", "*")
		defer os.Remove(tmp.Name())

		tmp.Write(buf)
		tmp.Close()

		cmd := exec.Command("ffprobe", "-v", "error", "-select_streams", "v:0",
			"-show_entries", "stream=width,height", "-of", "json", tmp.Name())
		out, _ := cmd.Output()

		var data struct {
			Streams []struct {
				Width  int `json:"width"`
				Height int `json:"height"`
			} `json:"streams"`
		}
		json.Unmarshal(out, &data)

		width := data.Streams[0].Width
		height := data.Streams[0].Height

		if width > MaxDimensionSize || height > MaxDimensionSize {

			json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": fmt.Sprintf("Слишком большой размер, макс.: %d", MaxDimensionSize)})
			return
		}
		result = processVideo(buf, tmp, name, format, width, height)

	default:

		json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": fmt.Sprintf("Формат не поддерживается: %s", mime)})
		return
	}

	json.NewEncoder(w).Encode(map[string]any{"ok": true, "result": result})
}

func getFile(w http.ResponseWriter, r *http.Request) {

	name := filepath.Base(r.URL.Path)
	path := filepath.Join(StoragePath, name[:2], name[2:4], name[4:6], name)

	f, _ := os.Open(path)
	defer f.Close()

	io.Copy(w, f)
}

func deleteFile(w http.ResponseWriter, r *http.Request) {

	name := filepath.Base(r.URL.Path)

	path := filepath.Join(StoragePath, name[:2], name[2:4], name[4:6], name)

	os.Remove(path)

	json.NewEncoder(w).Encode(map[string]any{"ok": true})
}
