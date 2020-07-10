<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SearchImage;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\ElasticSearchRepository;

final class SearchImage
{
    /** @var ElasticSearchRepository */
    private $elasticRepository;

    /** @param ElasticSearchRepository $repository */
    public function __construct(ElasticSearchRepository $repository)
    {
        $this->elasticRepository = $repository;
    }

    public function __invoke(SearchImageRequest $request): array
    {
        $searchTerm = $request->searchTerm();
        return $this->elasticRepository->search($searchTerm);
    }
}