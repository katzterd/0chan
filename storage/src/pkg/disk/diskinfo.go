package disk

import (
	"log"
	"syscall"
	"time"
)

type DiskManager struct {
	state DiskInfo
	timer *time.Ticker
}

type DiskInfo struct {
	Available uint64
	Free      uint64
	Total     uint64
}

var disk DiskManager

func Init() {

	Check()

	disk.timer = time.NewTicker(30 * time.Second)

	go func() {
		for range disk.timer.C {
			Check()
		}
	}()
}

func Stop() {

	if disk.timer != nil {
		disk.timer.Stop()
	}
}

func Check() {

	var stat syscall.Statfs_t

	err := syscall.Statfs("storage", &stat)
	if err != nil {
		log.Println(err)
		return
	}

	available := stat.Bavail * uint64(stat.Bsize)
	free := stat.Bfree * uint64(stat.Bsize)
	total := stat.Blocks * uint64(stat.Bsize)

	disk.state = DiskInfo{
		Available: available,
		Free:      free,
		Total:     total,
	}
}

func State() DiskInfo {

	return disk.state
}
