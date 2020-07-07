<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ViewImage;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\ImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\RedisCacheRepository;

final class ViewImage
{
    /** @var ImageRegisterRepository */
    private $imageRegisterRepository;

    /** @var RedisCacheRepository */
    private $redisRepository;

    /**
     * @param ImageRegisterRepository $imageRegisterRepository
     * @param RedisCacheRepository    $redisRepository
     */
    public function __construct(ImageRegisterRepository $imageRegisterRepository, RedisCacheRepository $redisRepository)
    {
        $this->imageRegisterRepository = $imageRegisterRepository;
        $this->redisRepository = $redisRepository;
    }

    public function __invoke(): array
    {
        if ($this->redisRepository->find('images')) {
            //echo 'Entra en REDIS';
            $images = $this->redisRepository->findAll('images');
        } else {
            //echo 'My Gallery MySQL:';
            $images = $this->imageRegisterRepository->findAll();
            $this->redisRepository->saveAll('images', $images);
        }

        return $images;
    }
}