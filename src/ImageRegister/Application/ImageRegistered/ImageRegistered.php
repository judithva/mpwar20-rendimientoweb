<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageRegistered;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\Image;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Service\ImageRegister as DomainImageRegister;

final class ImageRegistered
{
    /** @var DomainImageRegister */
    private $imageRegister;

    public function __construct($imageRegister)
    {
        $this->imageRegister = $imageRegister;
    }

    /**
     * @param ImageRegisteredRequest
     * @return Image
     */
    public function __invoke($imageRegisteredRequest)
    {
        $image = new Image($imageRegisteredRequest->nameImage(), $imageRegisteredRequest->pathImage());
        /*echo '<pre>';
        echo 'Image::';
        var_dump($image);
        echo '</pre>';*/

        $processedImage = $this->imageRegister->processing($image);
        /*echo '<pre>';
        var_dump($processedImage);
        echo '</pre>';*/

        $imageRegister = new ImageRegister(ImageId::generate(), $image, $imageRegisteredRequest->tagImage(), $imageRegisteredRequest->descriptionImage());
        /*echo '<pre>';
        echo 'ImageRegister::';
        var_dump($imageRegister);
        echo '<pre>';*/

        return $processedImage;
    }
}