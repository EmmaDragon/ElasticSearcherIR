<?php

require_once '../../../bin/php/php7.0.33/vendor/autoload.php';
require_once 'configuration.php';

class Indexer {
    

    private function openConnection() {
        $hosts = [
            [
                'host' => HOST,
                'port' => PORT,
                'scheme' => SCHEME,
                'user' => USER,
                'pass' => USER
            ]
        ];
        $client = Elasticsearch\ClientBuilder::create() 
                ->setHosts($hosts)
                ->build();
        return $client;
    }

    public function createIndex() {
        $client = $this->openConnection();
        $params['index'] = INDEX;
        $response = $client->indices()->create($params);
        print_r($response); 
    }

    public function deleteIndex() {
        $client = $this->openConnection();
        $params = [
            'index' => INDEX
        ];
        $response = $client->indices()->delete($params);
        print_r($response);
    }
    public function indexFile($title,$body,$time,$size)
    {
        $client = $this->openConnection();
        $params = [           
                'index' => INDEX, 
                'type' => 'doc',   
                'body' => [
                    'identifier' => uniqid(),
                    'last_modified' => $time,
                    'link' => $title,
                    'body' => $body,
                    'date_uploaded' => date("m/d/Y, h:i:s "),
                    'size' => $size
                ]
            ];
        $params['timestamp'] = strtotime("-1d");
        $response = $client->index($params);
        $client->indices()->flush([
            'index' => INDEX
        ]);

    }
    public function indexFiles($folderPath) {
        $client = $this->openConnection();
        $files = glob($folderPath);
        foreach ($files as $file) {
            $filename = basename($file); 
            $size=filesize($file)/1024;
            $size=strval(round($size,2))."KB";
            $params = [           
                'index' => INDEX, 
                'type' => 'doc',   
                'body' => [
                    'identifier' => uniqid(),
                    'last_modified' => date("m/d/Y, h:i:s ", filemtime($file)),
                    'link' => $filename,
                    'body' => file_get_contents($file),
                    'date_uploaded' => date("m/d/Y, h:i:s "),
                    'size' => $size
                ]
            ];
            $params['timestamp'] = strtotime("-1d");
            $response = $client->index($params);
            print_r($response);
        }
        $client->indices()->flush([
            'index' => INDEX
        ]);
    }

}
