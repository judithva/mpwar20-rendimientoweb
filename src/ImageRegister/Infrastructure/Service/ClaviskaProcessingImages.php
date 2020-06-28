<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Service;


use claviska\SimpleImage;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Domain\Service\ImageRegister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ClaviskaProcessingImages extends AbstractController implements ImageRegister
{
    public $simpleImage;

    public function __construct()
    {
        $this->simpleImage = new SimpleImage();
    }

    /**
     * @inheritDoc
     */
    public function processing($image)
    {
        try{
            $this->imageProcessedSepia($image);
            $this->imageProcessedWhiteAndBlack($image);
            $this->imageProcessedColorize($image);
            $this->imageProcessedInverted($image);
            $this->imageProcessedFrame($image);

        }catch(\Exception $exception){
            echo 'Error de SimpleImage de :' . $exception->getMessage();
        }
    }

    public function getPath($nameImage)
    {
        //$pathImage = __DIR__ .'/../../../public/assets/img/';
        $pathImage = $this->getParameter(  'app.processing_folder') . DIRECTORY_SEPARATOR;
        return $pathImage . $nameImage .'.png';
    }

    public function imageProcessedSepia($image)
    {
        //var_dump($this->getPath($image->nameImage() . '_sepia'));

        $this->simpleImage
            ->fromFile($image->pathImage())
            ->bestFit(300,600)
            ->sepia()
            ->toFile($this->getPath($image->nameImage() . '_sepia'), 'image/png');
    }

    public function imageProcessedWhiteAndBlack($image)
    {
        //var_dump($this->getPath($image->nameImage() . '_bn'));

        $this->simpleImage
            ->fromFile($image->pathImage())
            ->bestFit(300,600)
            ->desaturate()
            ->toFile($this->getPath($image->nameImage()  . '_bn'), 'image/png');
    }

    public function imageProcessedColorize($image)
    {
        //var_dump($this->getPath($image->nameImage()  . '_dr'));

        $this->simpleImage
            ->fromFile($image->pathImage())
            ->bestFit(300,600)
            ->colorize('DarkGreen')
            ->toFile($this->getPath($image->nameImage()  . '_dr'), 'image/png');
    }

    public function imageProcessedFrame($image)
    {
        //var_dump($this->getPath($image->nameImage()  . '_fr'));

        $this->simpleImage
            ->fromFile($image->pathImage())
            ->bestFit(300,600)
            ->border('black',5)
            ->toFile($this->getPath($image->nameImage()  . '_fr'), 'image/png');
    }

    public function imageProcessedInverted($image)
    {
        //var_dump($this->getPath($image->nameImage()  . '_x'));

        $this->simpleImage
            ->fromFile($image->pathImage())
            ->bestFit(300,600)
            ->flip('x')
            ->toFile($this->getPath($image->nameImage()  . '_x'), 'image/png');
    }
}