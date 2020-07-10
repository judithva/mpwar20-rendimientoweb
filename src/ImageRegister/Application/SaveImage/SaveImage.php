<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SaveImage;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\ImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\ElasticSearchRepository;

final class SaveImage
{
    private const NOFILTER = 'sin filtro';

    /** @var ImageRegisterRepository */
    private $imageRegisterRepository;

    /** @var ElasticSearchRepository */
    private $elasticRepository;

    /**
     * @param ImageRegisterRepository $imageRegisterRepository
     * @param ElasticSearchRepository $repository
     */
    public function __construct(ImageRegisterRepository $imageRegisterRepository, ElasticSearchRepository $repository)
    {
        $this->imageRegisterRepository = $imageRegisterRepository;
        $this->elasticRepository = $repository;
    }

    public function __invoke(SaveImageRequest $request)
    {
        $imageId = ImageId::generate();

        $imageRegister = new ImageRegister(
            $imageId,
            $request->name(),
            $request->path(),
            $request->ext(),
            self::NOFILTER,
            $request->tag() .' '.self::NOFILTER,
            $request->description(),
            null
        );

        $this->imageRegisterRepository->save($imageRegister);
        $this->elasticRepository->save($imageRegister);
    }
}