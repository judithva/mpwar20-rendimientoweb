<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject;



final class Image
{
    /** @var ImageId */
    private $id;

    /** @var string  */
    private $baseImage;
    private $pathImage;

    /**
     * @param string $baseImage
     * @param string $pathImage
     */
    public function __construct($baseImage, $pathImage)
    {
        $this->id = ImageId::generate();
        $this->baseImage = $baseImage;
        $this->pathImage = $pathImage;
    }

    /**
     * @return ImageId
     */
    public function id()
    {
        return $this->id;
    }

    /**
    * @return string
    */
    public function baseImage()
    {
        return $this->baseImage;
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
    public function nameImage()
    {
       return substr_replace($this->baseImage,'',  stripos($this->baseImage, '.'));
    }
}