<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence;

use Redis;

final class RedisCache
{
    public static function instanceREDIS(): Redis
    {
        $redis = new Redis();
        $redis->connect('redis');

        return $redis;
    }
}