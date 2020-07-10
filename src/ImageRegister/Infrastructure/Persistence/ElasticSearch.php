<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence;

use Elastica\Client;

final class ElasticSearch
{
    public static function instanceElastic(): Client
    {
        return new Client(['host' => 'elasticsearch', 'port' => 9200]);
    }
}