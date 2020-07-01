<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SaveImage;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\ImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\ValueObject\ImageId;

final class SaveImage
{
    /** @var ImageRegisterRepository */
    private $imageRegisterRepository;

    /** @param ImageRegisterRepository $imageRegisterRepository */
    public function __construct(ImageRegisterRepository $imageRegisterRepository)
    {
        $this->imageRegisterRepository = $imageRegisterRepository;
    }

    public function __invoke(SaveImageRequest $request)
    {
        $imageId = ImageId::generate();

        $imageRegister = new ImageRegister(
            $imageId,
            $request->name(),
            $request->path(),
            $request->ext(),
            null,
            $request->tag(),
            $request->description(),
            null
        );

        $this->imageRegisterRepository->save($imageRegister);
    }
}