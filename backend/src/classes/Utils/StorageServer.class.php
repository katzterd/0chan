<?php

class StorageServer {
    const SERVICE_NAME = 'storage';
    const SERVICE_PORT = '228';

    const RESERVED_SPACE_PERCENT = 0.05;

    /**
     * @return StorageServer
     * @throws IOException
     */
    public static function get()
    {
        $stats = self::request('http://' . static::SERVICE_NAME . ':' . self::SERVICE_PORT . '/stats');

        $totalSpace = $stats['self']['disk']['total'];
        $availableSpace = $stats['self']['disk']['available'];

        $reservedSpace = min($totalSpace * self::RESERVED_SPACE_PERCENT, 1 * pow(1024, 3) );
        if ($totalSpace - $reservedSpace <= 0) {
            $percentFree = 0;
        }
        
        if (!$stats['ok'] && $percentFree == 0) {
            throw new IOException('Нет доступного хранилища');
            return null;
        }


        return new self();
    }

    /**
     * @param $url
     * @return mixed
     * @throws IOException
     */
    protected static function request($url)
    {
        $response = file_get_contents($url);
        if ($response === false) {
            throw new IOException('request ' . $url . ' failed');
        }
        $data = json_decode($response, true);
        if ($data === false) {
            throw new IOException('decode failed: ' . $response);
        }
        return $data;
    }

    /**
     * @param $blob
     * @return \Psr\Http\Message\StreamInterface
     * @throws IOException
     * @throws NetworkException
     */
    public function uploadImage($blob)
    {
        $t = microtime(1);
        $client = new GuzzleHttp\Client();
        $response = $client->request(
            'POST',
            'http://' . static::SERVICE_NAME . ':' . self::SERVICE_PORT . '/file',
            [
                'multipart' => [
                    [
                        'name' => 'file',
                        'contents' => $blob,
                        'headers' => [
                            'Content-Type' => 'application/octet-stream'
                        ],
                        'filename' => 'blob'
                    ]
                ]
            ]
        );
        if ($response->getStatusCode() != 200) {
            throw new NetworkException($response->getReasonPhrase());
        }
        $data = json_decode((string)$response->getBody(), true);
        if (!$data) {
            throw new IOException('could not decode: ' . $response->getBody());
        }
        if (!$data['ok']) {
            throw new IOException('error: ' . $data['error']);
        }
        return $data['result'] + [ 't' => microtime(1) - $t ];
    }

    public function deleteFile($filename)
    {
        $client = new GuzzleHttp\Client();
        $response = $client->request(
            'DELETE',
            'http://'. static::SERVICE_NAME . ':' . self::SERVICE_PORT . '/' . $filename
        );
        if ($response->getStatusCode() != 200) {
            throw new NetworkException($response->getReasonPhrase());
        }
        $data = json_decode((string)$response->getBody(), true);
        if (!$data) {
            throw new IOException('could not decode: ' . $response->getBody());
        }
        if (!$data['ok']) {
            throw new IOException('error: ' . $data['error']);
        }
        return $this;
    }
}
