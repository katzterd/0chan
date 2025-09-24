package util

import (
	"math/rand"
	"strconv"
	"time"
)

func MakeFilename() string {
	d := time.Now()
	name := strconv.Itoa(d.Year()%100) + z(int(d.Month())) + z(d.Day())
	for range 12 {
		name += strconv.FormatInt(int64(rand.Intn(26)+10), 36)
	}
	return name
}

func z(v int) string {
	if v < 10 {
		return "0" + strconv.Itoa(v)
	}
	return strconv.Itoa(v)
}
