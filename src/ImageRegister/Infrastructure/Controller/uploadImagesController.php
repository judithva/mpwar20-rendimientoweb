<?php
declare(strict_type=1);

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Controller;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageRegistered\ImageRegistered;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\ImageRegistered\ImageRegisteredRequest;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Form\ImageType;
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
    /** @var ImageRegistered */
    private $imageRegistered;

    /**
     * @param ImageRegistered
     */
    public function __construct($imageRegistered)
    {
        $this->imageRegistered = $imageRegistered;
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

        if($request->isMethod('POST')) {
            if($form->isSubmitted() && $form->isValid()) {
                try{
                    $files = $request->files->get('file');
                    $tags = $form->getNormData()['tags'];
                    $description = $form->getNormData()['description'];

                    foreach ($files as $file) {
                        $newFileName= $this->getNewImage($file);
                        $targetFile = $this->getPath() . $newFileName;
                        /*echo '<pre>';
                        var_dump('Tags: '.$tags);
                        var_dump('Descripcion: '. $description);
                        var_dump('newFileName::'.$newFileName);
                        var_dump('TargetPath:::'.$this->getPath());
                        var_dump('TargetFile:::'.$targetFile);
                        echo '</pre>';*/
                        try{
                            $file->move($this->getPath(), $newFileName);
                            $image = $this->imageRegistered->__invoke(
                                new ImageRegisteredRequest($newFileName,$targetFile, $tags, $description)
                            );

                        }catch (FileException $fileException){
                            echo 'Error al mover imagen' . $fileException->getMessage();
                        }
                    }

                }catch (Exception $exception){
                    $form->get('tags')->addError(new FormError($exception->getMessage()));
                    return $this->render(
                        'dropzone/dropzone.html.twig',['form' => $form->createView()]
                    );
                }
            }
        }

        return $this->render(
            'dropzone/dropzone.html.twig',['form' => $form->createView()]
        );
    }

    private function getNewImage($file)
    {
        $originalNameFile =  $file->getClientOriginalName();
        $ext = pathinfo($originalNameFile, PATHINFO_EXTENSION);

        return time().'.'.$ext;
    }

    private function getPath()
    {
        return DIRECTORY_SEPARATOR . $this->getParameter('app.storage_folder') . DIRECTORY_SEPARATOR;
    }
}