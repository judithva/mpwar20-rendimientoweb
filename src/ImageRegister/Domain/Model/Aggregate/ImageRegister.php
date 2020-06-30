<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;


final class ImageRegister
{
    /** @var ImageId */
    private $id;
    /** @var string */
    private $imageName;
    private $imagePath;
    private $imageExt;
    private $imageFilter;
    private $tags;
    private $description;
    private $newPathImage;

    /**
     * @param ImageId | null
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     * @param string
     */
    public function __construct($id = null, string $imageName, string $imagePath, string $imageExt, string $imageFilter, string $tags, string $description, string $newPathImage)
    {
        $this->id = $id ?? ImageId::generate();
        $this->imageName = $imageName;
        $this->imagePath = $imagePath;
        $this->imageExt = $imageExt;
        $this->imageFilter = $imageFilter;
        $this->tags = $tags;
        $this->description = $description;
        $this->newPathImage = $newPathImage;
    }

    /**
     * @return ImageId
     */
    public function id(): ?ImageId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function imageName(): string
    {
        return $this->imageName;
    }

    /**
     * @return string
     */
    public function imagePath(): string
    {
        return $this->imagePath;
    }

    /**
     * @return string
     */
    public function imageExt(): string
    {
        return $this->imageExt;
    }

    /**
     * @return string
     */
    public function imageFilter(): ?string
    {
        return $this->imageFilter;
    }

    /**
     * @return string
     */
    public function tags(): string
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function newPathImage(): string
    {
        return $this->newPathImage;
    }

    /**
     * @return string
     */
    public function baseName()
    {
        return substr_replace($this->imageName(),'',  stripos($this->imageName(), '.'));
    }

    /**
     * @return string
     */
    public function message(): string
    {
        $parameter = [
            "id" => $this->id(),
            "imageName" => $this->baseName(),
            "imagePath" => $this->imagePath(),
            "imageExt" => $this->imageExt(),
            "filter" => null,
            "tag" => $this->tags(),
            "description" => $this->description(),
            "processedImage" => null,
            "processedImagePath" => $this->newPathImage()
        ];

        return json_encode($parameter, JSON_UNESCAPED_SLASHES);
    }
}