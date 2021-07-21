<?php

namespace App\Elasticsearch;

use Elasticsearch\ClientBuilder;

class BooksLibraryElasticsearch
{
    private $client;

    public function __construct()
    {

        $hosts = [ 'elasticsearch:9200' ];
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function findAllBooks($index)
    {
        $params = [
            'index' => $index,
            'size' => 1000
        ];

        return $this->client->search($params);

    }

    public function findBooksByFilter($index, $filterType, $filterValue)
    {
        $params = [
            'index' => $index,
            'body'  => [
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    $filterType => $filterValue
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'size' => 1000
        ];

        return $this->client->search($params);

    }

    public function checkIfIndexExists($index)
    {
        return $this->client->indices()->exists(['index' => $index]);
    }

    public function createIndex($index)
    {
        $params = [
            'index' => $index,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 1
                ]
            ]
        ];

        return $this->client->indices()->create($params);;
    }

    public function getAliases($index)
    {
        return $this->client->indices()->getAliases(['index' => $index]);
    }

    public function bulkQuery($params)
    {
        return $this->client->bulk($params);
    }

    public function refresh($index)
    {
        $params = [
        'index' => $index,
        'type' => 'refresh',
        'id' => 'refresh',
        'refresh' => true,
        'body' => []
        ];

        $this->client->index($params);
    }

    public function createAlias($index, $alias)
    {
        $params['body'] = array(
            'actions' => array(
                array(
                    'add' => array(
                        'index' => $index,
                        'alias' => $alias
                    )
                )
            )
        );
        
        $this->client->indices()->updateAliases($params);
    }
}