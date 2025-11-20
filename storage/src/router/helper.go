package router

import (
	"bytes"
	"crypto/md5"
	"encoding/hex"
	"encoding/json"
	"fmt"
	"image"
	"image/color"
	"image/gif"
	"os"
	"os/exec"
	"path/filepath"

	"github.com/HugoSmits86/nativewebp"
	"github.com/disintegration/imaging"
)

func processImage(b []byte, name string) (map[int]map[string]any, error) {

	result := map[int]map[string]any{}

	img, format, _ := image.Decode(bytes.NewReader(b))

	if img.Bounds().Dx() > MaxDimensionSize || img.Bounds().Dy() > MaxDimensionSize {
		return nil, fmt.Errorf("слишком большой размер, макс.: %d", MaxDimensionSize)
	}

	for _, size := range Sizes {

		var (
			buf    bytes.Buffer
			dstImg image.Image
		)

		if size > 0 {
			dstImg = imaging.Fit(img, size, size, imaging.Lanczos)
			dstImg = imaging.Overlay(imaging.New(dstImg.Bounds().Dx(), dstImg.Bounds().Dy(), color.White), dstImg, image.Point{0, 0}, 1.0)
			imaging.Encode(&buf, dstImg, imaging.JPEG)
			buf.Write(fmt.Appendf(nil, "%d", size))
		} else {
			dstImg = img
			switch format {
			case "png":
				imaging.Encode(&buf, dstImg, imaging.PNG)
			case "jpeg":
				imaging.Encode(&buf, dstImg, imaging.JPEG)
			case "jpg":
				imaging.Encode(&buf, dstImg, imaging.JPEG)
			case "webp":
				nativewebp.Encode(&buf, dstImg, &nativewebp.Options{UseExtendedFormat: true})
			}
		}

		fullname := name
		if size > 0 {
			fullname += "-" + fmt.Sprint(size) + ".jpg"
		} else {
			fullname += fmt.Sprintf(".%s", format)
		}

		path := filepath.Join(StoragePath, fullname[:2], fullname[2:4], fullname[4:6])
		os.MkdirAll(path, 0777)
		os.WriteFile(filepath.Join(path, fullname), buf.Bytes(), 0666)

		md5 := md5.Sum(buf.Bytes())

		result[size] = map[string]any{
			"name":   fullname,
			"width":  dstImg.Bounds().Dx(),
			"height": dstImg.Bounds().Dy(),
			"md5":    hex.EncodeToString(md5[:]),
			"size":   buf.Len(),
		}

	}
	return result, nil

}

func processGif(b []byte, name string) (map[int]map[string]any, error) {

	result := map[int]map[string]any{}

	g, _ := gif.DecodeAll(bytes.NewReader(b))

	if g.Image[0].Bounds().Dx() > MaxDimensionSize || g.Image[0].Bounds().Dy() > MaxDimensionSize {
		return nil, fmt.Errorf("слишком большой размер, макс.: %d", MaxDimensionSize)
	}

	for _, size := range Sizes {

		var (
			buf   bytes.Buffer
			thumb image.Image
		)

		if size > 0 {
			thumb = imaging.Fit(g.Image[0], size, size, imaging.Lanczos)
			thumb = imaging.Overlay(imaging.New(thumb.Bounds().Dx(), thumb.Bounds().Dy(), color.White), thumb, image.Point{0, 0}, 1.0)
			imaging.Encode(&buf, thumb, imaging.JPEG)
		} else {
			gif.EncodeAll(&buf, g)
		}

		fullname := name
		if size > 0 {
			fullname += "-" + fmt.Sprint(size) + ".jpg"
		} else {
			fullname += ".gif"
		}

		path := filepath.Join(StoragePath, fullname[:2], fullname[2:4], fullname[4:6])
		os.MkdirAll(path, 0777)
		os.WriteFile(filepath.Join(path, fullname), buf.Bytes(), 0666)

		md5 := md5.Sum(buf.Bytes())

		if size > 0 {
			result[size] = map[string]any{
				"name":   fullname,
				"width":  thumb.Bounds().Dx(),
				"height": thumb.Bounds().Dy(),
				"md5":    hex.EncodeToString(md5[:]),
				"size":   buf.Len(),
			}
		} else {
			result[0] = map[string]any{
				"name":   fullname,
				"width":  g.Config.Width,
				"height": g.Config.Height,
				"md5":    hex.EncodeToString(md5[:]),
				"size":   buf.Len(),
			}
		}

	}
	return result, nil

}

func processVideo(video []byte, name, format string) (map[int]map[string]any, error) {

	result := map[int]map[string]any{}

	tmp, _ := os.CreateTemp("", "*")
	defer os.Remove(tmp.Name())

	tmp.Write(video)
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

	if data.Streams[0].Width > MaxDimensionSize || data.Streams[0].Height > MaxDimensionSize {
		return nil, fmt.Errorf("слишком большой размер, макс.: %d", MaxDimensionSize)
	}

	for _, size := range Sizes {

		if size > 0 {

			var buf bytes.Buffer

			tmpThumb, _ := os.CreateTemp("", "thumb-*.jpg")
			tmpThumb.Close()

			args := []string{"-i", tmp.Name(), "-ss", "0", "-vframes", "1", "-filter:v", fmt.Sprintf("scale=%d:%d:force_original_aspect_ratio=1", size, size), "-y", tmpThumb.Name()}

			exec.Command("ffmpeg", args...).Run()

			thumb, _ := os.ReadFile(tmpThumb.Name())

			img, _, _ := image.Decode(bytes.NewReader(thumb))

			imaging.Encode(&buf, img, imaging.JPEG)

			md5 := md5.Sum(buf.Bytes())

			fullname := name + "-" + fmt.Sprint(size) + ".jpg"
			path := filepath.Join(StoragePath, fullname[:2], fullname[2:4], fullname[4:6])

			os.MkdirAll(path, 0777)
			os.WriteFile(filepath.Join(path, fullname), buf.Bytes(), 0666)

			result[size] = map[string]any{
				"name":   fullname,
				"width":  img.Bounds().Dx(),
				"height": img.Bounds().Dy(),
				"md5":    hex.EncodeToString(md5[:]),
				"size":   buf.Len(),
			}

			os.Remove(tmpThumb.Name())

		} else {

			fullname := name + fmt.Sprintf(".%s", format)
			path := filepath.Join(StoragePath, fullname[:2], fullname[2:4], fullname[4:6])

			os.MkdirAll(path, 0777)
			os.WriteFile(filepath.Join(path, fullname), video, 0666)

			md5 := md5.Sum(video)

			result[0] = map[string]any{
				"name":   fullname,
				"width":  data.Streams[0].Width,
				"height": data.Streams[0].Height,
				"md5":    hex.EncodeToString(md5[:]),
				"size":   len(video),
			}
		}
	}
	return result, nil
}
