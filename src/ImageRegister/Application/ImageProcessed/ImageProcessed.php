<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageProcessed;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Model\Aggregate\ImageRegister;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Service\ImageProcess;

final class ImageProcessed
{
    /** @var ImageProcess */
    private $imageProcess;

    public function __construct(ImageProcess $imageProcess)
    {
        $this->imageProcess = $imageProcess;
    }

    /**
     * @param ImageProcessedRequest
     */
    public function __invoke(ImageProcessedRequest $imageProcessedRequest)
    {
        $imageToProcess = new ImageRegister(
            null,
            $imageProcessedRequest->nameImage(),
            $imageProcessedRequest->pathImage(),
            $imageProcessedRequest->extImage(),
            '',
            $imageProcessedRequest->tagImage(),
            $imageProcessedRequest->descriptionImage(),
            $imageProcessedRequest->newPathImage()
        );

        //Llamar al metodo Save, Guardar la imagen original de $imageToProcess, que contiene Uuid

        $message =  $imageToProcess->message();

        $this->imageProcess->send($message);
    }
}