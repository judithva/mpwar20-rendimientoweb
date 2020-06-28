<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Service;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\Image;

interface ImageRegister
{
    /**
     * @param Image
     * @return Image
     */
    public function processing($image);
}