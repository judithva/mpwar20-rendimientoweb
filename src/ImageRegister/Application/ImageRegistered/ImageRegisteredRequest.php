<?php
declare(strict_type=1);


namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageRegistered;


final class ImageRegisteredRequest
{
    /** @var string */
    private $nameImage;
    private $pathImage;
    private $tagImage;
    private $descriptionImage;

    /**
     * @param string $nameImage
     * @param string $pathImage
     * @param string $tagImage
     * @param string $descriptionImage
     */
    public function __construct($nameImage, $pathImage, $tagImage, $descriptionImage)
    {
        $this->nameImage = $nameImage;
        $this->pathImage = $pathImage;
        $this->tagImage = $tagImage;
        $this->descriptionImage = $descriptionImage;
    }

    /**
     * @return string
     */
    public function nameImage()
    {
        return $this->nameImage;
    }

    /**
     * @return string
     */
    public function pathImage()
    {
        return $this->pathImage;
    }

    /**
     * @return string
     */
    public function tagImage()
    {
        return $this->tagImage;
    }

    /**
     * @return string
     */
    public function descriptionImage()
    {
        return $this->descriptionImage;
    }
}