<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\Image;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;



final class ImageRegister
{
    /** @var ImageId */
    private $id;
    /** @var Image*/
    private $imageOriginal;
    /** @var string */
    private $tags;
    private $description;

    /**
     * @param ImageId
     * @param Image
     * @param string
     * @param string
     */
    public function __construct($id,  $imageOriginal,  $tags,  $description)
    {
        $this->id = $id;
        $this->imageOriginal = $imageOriginal;
        $this->tags = $tags;
        $this->description = $description;
    }

    /**
     * @return ImageId
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Image
     */
    public function imageOriginal()
    {
        return $this->imageOriginal;
    }

    /**
     * @return string
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }
}