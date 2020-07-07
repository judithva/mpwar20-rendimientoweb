<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Controller;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ViewImage\ViewImage;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\RedisCacheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\MySQLImageRegisterRepository;

final class GalleryImagesController extends AbstractController
{
    /** @var ViewImage */
    private $viewImage;

    public function __construct(ViewImage $viewImage)
    {
       $this->viewImage = $viewImage;
    }

    /** @Route("/gallery", name="gallery", methods={"POST"}) */
    public function gallery()
    {
        $this->viewImage->__construct(new MySQLImageRegisterRepository(), new RedisCacheRepository());
        $images = $this->viewImage->__invoke();

        return $this->render('gallery/gallery.html.twig', ['images' => $images] );
    }
}