package main

import (
	"log"
	"net/http"
	"os"
	"os/signal"
	"storage/pkg/disk"
	"storage/router"
)

func main() {

	srv := &http.Server{
		Handler: router.Setup(),
		Addr:    ":228",
	}

	disk.Init()

	log.Println("storage started")
	if err := srv.ListenAndServe(); err != nil {
		log.Fatal(err)
	}

	c := make(chan os.Signal, 1)
	signal.Notify(c, os.Interrupt)

	if <-c == os.Interrupt {
		srv.Close()
	}

}
