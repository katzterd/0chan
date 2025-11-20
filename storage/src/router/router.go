package router

import (
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
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

	b, _ := io.ReadAll(f)
	mime := http.DetectContentType(b)

	isImage := mime == "image/jpeg" || mime == "image/png" || mime == "image/webp"
	isVideo := mime == "video/mp4" || mime == "video/webm"
	isGif := mime == "image/gif"

	var result map[int]map[string]any
	var err error

	switch {
	default:
		json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": fmt.Sprintf("Формат не поддерживается: %s", mime)})
		return
	case isImage:
		result, err = processImage(b, util.MakeFilename())
		if err != nil {
			json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": err.Error()})
			return
		}
	case isGif:
		result, err = processGif(b, util.MakeFilename())
		if err != nil {
			json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": err.Error()})
			return
		}
	case isVideo:
		var format string
		switch mime {
		case "video/webm":
			format = "webm"
		case "video/mp4":
			format = "mp4"
		}
		result, err = processVideo(b, util.MakeFilename(), format)
		if err != nil {
			json.NewEncoder(w).Encode(map[string]any{"ok": false, "error": err.Error()})
			return
		}
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
