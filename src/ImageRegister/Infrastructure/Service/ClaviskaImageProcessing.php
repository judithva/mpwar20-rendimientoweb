<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Service;


use claviska\SimpleImage;

final class ClaviskaImageProcessing
{
    /** @var SimpleImage */
    public $simpleImage;

    public function __construct()
    {
        $this->simpleImage = new SimpleImage();
    }

    /**
     * @param array $parameters
     */
    public function processing(array $parameters)
    {
        try {
            $this->simpleImage
                ->fromFile($parameters['imagePath'])
                ->bestFit(300, 600);

                switch ($parameters['filter']) {
                    case 'prd':
                        $this->simpleImage->sepia();
                        break;
                    case 'bw':
                        $this->simpleImage->desaturate();
                        break;
                    case 'ctr':
                        $this->simpleImage->colorize('DarkGreen');
                        break;
                    case 'frm':
                        $this->simpleImage->border('black',5);
                        break;
                    case 'flp':
                        $this->simpleImage->flip('x');
                        break;
                }
            $this->simpleImage->toFile($parameters['processedImagePath'],'image/png');

        } catch (\Exception $exception) {
            echo 'Error processing image:' . $exception->getMessage();
        }
    }
}