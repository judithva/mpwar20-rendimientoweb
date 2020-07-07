<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\RedisCache;

final class RedisCacheRepository
{
    /** @var RedisCache */
    private $redisClient;

    public function __construct()
    {
        $this->redisClient = RedisCache::instanceREDIS();
    }

    public function save(): void
    {
    }

    public function saveAll(string $key, array $arrayElements): void
    {
        $this->redisClient->set($key, json_encode($arrayElements));
    }

    public function find(string $key): ?string
    {
        if (!($this->redisClient->get($key))) {
            return null;
        }
        return $this->redisClient->get($key);
    }

    public function findAll(string $key): array
    {
        return json_decode($this->redisClient->get($key), true);
    }

    public function delete(string $key): void
    {
        $this->redisClient->del($key);
    }
}