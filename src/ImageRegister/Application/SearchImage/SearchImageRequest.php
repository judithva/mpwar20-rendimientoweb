<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SearchImage;


final class SearchImageRequest
{
    /** @var string */
    private $searchTerm;

    public function __construct(string $searchTerm)
    {
        $this->searchTerm = $searchTerm;
    }

    /**
     * @return string
     */
    public function searchTerm() :string
    {
        return $this->searchTerm;
    }
}