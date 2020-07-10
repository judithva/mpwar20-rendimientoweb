<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository;


use Elastica\Index;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\ElasticSearch;

final class ElasticSearchRepository
{
    /** @var ElasticSearch */
    private $elasticClient;

    /** @var Index */
    private $index;

    public function __construct()
    {
        $this->elasticClient = ElasticSearch::instanceElastic();
        $this->create();
    }

    public function create(): void
    {
        $this->index = $this->elasticClient->getIndex('images');
        if (!$this->index->exists()) {
            $this->index->create([]);
        }
    }

    public function save(ImageRegister $imageRegister): void
    {
        $imageId = $imageRegister->id()->getId();
        $imageName = $imageRegister->imageName();
        $imageFilter = $imageRegister->imageFilter();
        $tag = $imageRegister->tags();
        $description = $imageRegister->description();

        $document = $this->index->createDocument(
            $imageId,
            [
                "imageName"   => $imageName,
                "filter"      => $imageFilter,
                "tags"        => $tag,
                "description" => $description
            ]
        );

        $this->index->addDocument($document);
    }

    public function search(string $searchTerm): array
    {
        $result = $this->searchFuzzy($searchTerm);

        if (empty($result)) {
            $result = $this->searchMultiMatch($searchTerm);
        }

        if (empty($result)) {
            $result = $this->searchWildCard($searchTerm);
        }

        return $result;
    }

    public function searchWildCard(string $searchTerm): array
    {
        $result = $this->index->search(
            [
                "query" => [
                    "wildcard" =>
                        [
                            "tags" => '*' . $searchTerm . '*'
                        ]
                ]
            ]
        );
        return $result->getResults();
    }

    public function searchMultiMatch(string $searchTerm): array
    {
        $result = $this->index->search(
            [
                "query" => [
                    "multi_match" =>
                        [
                            "query"    => $searchTerm,
                            "type"     => "best_fields",
                            "fields"   =>
                                [
                                    "tags",
                                    "description"
                                ],
                            "operator" => "and"
                        ]
                ]
            ]
        );

        return $result->getResults();
    }

    public function searchFuzzy(string $searchTerm): array
    {
        $result = $this->index->search(
            [
                "query" => [
                    "fuzzy" =>
                        [
                            "tags" =>
                                [
                                    "value"          => $searchTerm,
                                    "boost"          => 1,
                                    "fuzziness"      => 2,
                                    "prefix_length"  => 0,
                                    "max_expansions" => 100
                                ]
                        ]
                ]
            ]
        );
        return $result->getResults();
    }
}