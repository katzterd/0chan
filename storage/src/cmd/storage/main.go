package main

import (
	"context"
	"log"
	"net/http"
	"os"
	"os/signal"
	"storage/pkg/disk"
	"storage/router"
	"syscall"
	"time"
)

func main() {

	srv := &http.Server{
		Handler: router.Setup(),
		Addr:    ":228",
	}

	disk.Init()

	go func() {
		if err := srv.ListenAndServe(); err != nil && err != http.ErrServerClosed {
			log.Fatal(err)
		}
	}()
	log.Println("storage started")

	s := make(chan os.Signal, 1)
	signal.Notify(s, os.Interrupt, syscall.SIGTERM)
	<-s
	ctx, c := context.WithTimeout(context.Background(), 5*time.Second)
	defer c()
	srv.Shutdown(ctx)

}
