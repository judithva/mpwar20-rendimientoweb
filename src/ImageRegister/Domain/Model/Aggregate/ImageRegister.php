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
    public function __construct($id = null, string $imageName, string $imagePath, string $imageExt, $imageFilter = null, string $tags, string $description, $newPathImage = null)
    {
        $this->id = $id ?? ImageId::generate();
        $this->imageName = $imageName;
        $this->imagePath = $imagePath;
        $this->imageExt = $imageExt;
        $this->imageFilter = $imageFilter ?? null;
        $this->tags = $tags;
        $this->description = $description;
        $this->newPathImage = $newPathImage ?? null;
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
        return $this->imageFilter ?? null;
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
    public function newPathImage(): ?string
    {
        return $this->newPathImage ?? null;
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
            "filter" => $this->imageFilter(),
            "tag" => $this->tags(),
            "description" => $this->description(),
            "processedImage" => null,
            "processedImagePath" => $this->newPathImage(),
            "textFilter" => null
        ];

        return json_encode($parameter, JSON_UNESCAPED_SLASHES);
    }
}