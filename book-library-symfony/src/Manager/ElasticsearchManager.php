<?php

namespace App\Manager;

class ElasticsearchManager
{
	public function isResponseSuccess($response)
    {
        return isset($response) && isset($response['_shards']) && isset($response['_shards']['successful']) && $response['_shards']['successful'] === 1;
    }

    public function getHits($response)
    {
        return isset($response) && isset($response['hits']) && isset($response['hits']['hits']) ? $response['hits']['hits'] : [];
    }
}