<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageProcessed;


final class ImageProcessedRequest
{
    /** @var string */
    private $iName;
    private $iPath;
    private $iExt;
    private $iTag;
    private $iDescription;
    private $newPath;

    /**
     * @param string $iName
     * @param string $iPath
     * @param string $iExt
     * @param string $iTag
     * @param string $iDescription
     * @param string $newPath
     */
    public function __construct($iName, $iPath, $iExt, $iTag, $iDescription, $newPath)
    {
        $this->iName = $iName;
        $this->iPath = $iPath;
        $this->iExt = $iExt;
        $this->iTag = $iTag;
        $this->iDescription = $iDescription;
        $this->newPath = $newPath;
    }

    /**
     * @return string
     */
    public function nameImage()
    {
        return $this->iName;
    }

    /**
     * @return string
     */
    public function pathImage()
    {
        return $this->iPath;
    }

    /**
     * @return string
     */
    public function extImage()
    {
        return $this->iExt;
    }

    /**
     * @return string
     */
    public function tagImage()
    {
        return $this->iTag;
    }

    /**
     * @return string
     */
    public function descriptionImage()
    {
        return $this->iDescription;
    }

    /**
     * @return string
     */
    public function newPathImage()
    {
        return $this->newPath;
    }
}