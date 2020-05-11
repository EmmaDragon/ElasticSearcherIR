<?php

require_once '../../../bin/php/php7.0.33/vendor/autoload.php';
require_once 'configuration.php';
require_once 'Book.php';

class Searcher {

    // Otvaranje konekcije se koristi više puta pa je dato kao posebna privatna 
    // funkcija. 
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
        $client = Elasticsearch\ClientBuilder::create()           // Instantiate a new ClientBuilder
                ->setHosts($hosts)      // Set the hosts
                ->build();
        return $client;
    }

    public function singleFieldSearch($field, $query) {
        error_reporting(E_ALL & ~E_WARNING);
        $client = $this->openConnection();

        $params = [
            'index' => INDEX,   
            'type' => 'doc',    
                               
            'body' => [
                'from' => 0,    
                'size' => 50,
                'query' => [
                    'match' => [
                        $field => $query  
                    ]
                ]
            ]
        ];

        $results = $client->search($params);
        $books=$this->convertResultToBookObject($results);
        return $books;
        
    }
     
    public function convertResultToBookObject($results)
    {
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
        $books = array();
        foreach ($results[hits][hits] as $file )
        {
            $id= $file[_id];
            $title = $file[_source][link];
            $content = $file[_source][body];
            $dateModified=$file[_source][last_modified];
            $rank = $file[_score];
            $size=$file[_source][size];
            $book = new Book($id,$title,$dateModified,$rank,$size);
            array_push($books, $book);
            
        }
        return $books;
    }
    public function convertResultToFullBook($results)
    {
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
        foreach ($results[hits][hits] as $file )
        {
            $id= $file[_id];
            $title = $file[_source][link];
            $content = $file[_source][body];
            $dateModified=$file[_source][last_modified];
            $rank = $file[_score];
            $size=$file[_source][size];
            $book = new Book($id,$title,$dateModified,$rank,$size);
            $book->content=$content;  
        }
        return $book;
    }
    public function searchDocumentsById($field, $query)
    {
        error_reporting(E_ALL & ~E_WARNING);
        $client = $this->openConnection();

        $params = [
            'index' => INDEX,   
            'type' => 'doc',    
                               
            'body' => [
                'from' => 0,    
                'size' => 50,
                'query' => [
                    'match' => [
                        $field => $query  
                    ]
                ]
            ]
        ];

        $results = $client->search($params);
        $book=$this->convertResultToFullBook($results);
        return $book;
        
    }

}
