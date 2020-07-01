<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SaveImage;


final class SaveImageRequest
{
    /** @var string */
    private $name;
    private $path;
    private $ext;
    private $tag;
    private $description;

    public function __construct(string $name, string $path, string $ext, string $tag, string $description)
    {
        $this->name = $name;
        $this->path = $path;
        $this->ext = $ext;
        $this->tag = $tag;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function name() :string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function path() :string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function ext() :string
    {
        return $this->ext;
    }


    /**
     * @return string
    */
    public function tag() :string
    {
        return $this->tag;
    }

    /**
     * @return string
     */
    public function description() :string
    {
        return $this->description;
    }
}