<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;

interface ImageRegisterRepository
{
    public function save(ImageRegister $imageRegister): void;

    public function find(ImageId $imageId): ?ImageRegister;

    public function findAll(): array;
}