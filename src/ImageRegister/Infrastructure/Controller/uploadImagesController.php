<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Controller;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageProcessed\ImageProcessed;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageProcessed\ImageProcessedRequest;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SaveImage\SaveImage;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SaveImage\SaveImageRequest;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Form\ImageType;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\ElasticSearchRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\MySQLImageRegisterRepository;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\RedisCacheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class uploadImagesController extends AbstractController
{
    /** @var ImageProcessed */
    private $imageProcessed;

    /** @var SaveImage */
    private $saveImage;

    /** @var RedisCacheRepository */
    private $redisRepository;

      /**
     * @param ImageProcessed
     * @param SaveImage
     */
    public function __construct(ImageProcessed $imageProcessed, SaveImage $saveImage)
    {
        $this->imageProcessed = $imageProcessed;
        $this->saveImage = $saveImage;
        $this->redisRepository = new RedisCacheRepository();
    }

    /**
     * @Route("/upload", name="upload", methods={"POST"})
     * @param Request $request
     *
     * @return RedirectResponse | Response
     */
    public function upload(Request $request)
    {
        $form = $this->createForm(ImageType::class, null);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $files = $request->files->get('file');
                    $tags = $form->getNormData()['tags'];
                    $description = $form->getNormData()['description'];

                    foreach ($files as $file) {
                        $newFileName = $this->getNewImage($file);
                        $targetFile = $this->getPath() . $newFileName;
                        $extFile = $this->getExtImage($file->getClientOriginalName());

                        /*echo '<pre>';
                        var_dump($file);
                        var_dump('newFileName::'.$newFileName);
                        var_dump('Tags: '.$tags);
                        var_dump('Descripcion: '. $description);
                        var_dump('extFile::'.$extFile);
                        var_dump('TargetPath:::'.$this->getPath());
                        var_dump('TargetFile:::'.$targetFile);
                        echo '</pre>';*/

                        try {
                            $this->redisRepository->delete('images');

                            $file->move($this->getPath(), $newFileName);

                            $imageMysqlRepository = new MySQLImageRegisterRepository();
                            $elasticRepository =new ElasticSearchRepository();
                            $this->saveImage->__construct($imageMysqlRepository, $elasticRepository);
                            $this->saveImage->__invoke(new SaveImageRequest(
                                $newFileName,
                                $targetFile,
                                $extFile,
                                $tags,
                                $description));

                            $this->imageProcessed->__invoke(
                                new ImageProcessedRequest(
                                    $newFileName,
                                    $targetFile,
                                    $extFile,
                                    $tags,
                                    $description,
                                    $this->getNewPath()
                                )
                            );

                        } catch (FileException $fileException) {
                            echo 'Error al mover imagen'
                                . $fileException->getMessage();
                        }
                    }
                } catch (Exception $exception) {
                    $form->get('tags')->addError(
                        new FormError($exception->getMessage())
                    );
                    return $this->render(
                        'dropzone/dropzone.html.twig',
                        ['form' => $form->createView()]
                    );
                }
            }
        }

        return $this->render(
            'dropzone/dropzone.html.twig',
            ['form' => $form->createView()]
        );
    }

    private function getExtImage($originalName)
    {
        return pathinfo($originalName, PATHINFO_EXTENSION);
    }

    private function getNewImage($file)
    {
        return time() . '.' . $this->getExtImage($file->getClientOriginalName());
    }

    private function getPath()
    {
        //return DIRECTORY_SEPARATOR . $this->getParameter('app.storage_folder') . DIRECTORY_SEPARATOR;
        return $this->getParameter('app.storage_folder').DIRECTORY_SEPARATOR;
    }

    private function getNewPath()
    {
        return $this->getParameter('app.processing_folder').DIRECTORY_SEPARATOR;
    }
}