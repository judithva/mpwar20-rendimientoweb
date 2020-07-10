<?php

namespace LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Controller;


use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SearchImage\SearchImage;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Application\SearchImage\SearchImageRequest;
use LaSalle\Rendimiento\JudithVilela\ImageRegister\Infrastructure\Persistence\Repository\ElasticSearchRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SearchImagesController extends AbstractController
{
    /** @var SearchImage */
    private $searchImage;

    public function __construct(SearchImage $searchImage)
    {
        $this->searchImage = $searchImage;
    }

    /**
     * @Route("/search", name="search", methods={"GET"})
     * @param Request $request
     *
     * @return RedirectResponse | Response
     */
    public function search(Request $request)
    {
        $results = null;

        if ($request->isMethod('GET')) {
            $searchTerm = $request->get('search');
            if (isset($searchTerm)) {
                try {
                    $this->searchImage->__construct(new ElasticSearchRepository());
                    $results = $this->searchImage->__invoke(new SearchImageRequest($searchTerm));
                } catch (Exception $exception) {
                    echo 'Error al realizar la búsqueda ' . $exception->getMessage();
                }
            }
        }

        return $this->render('search/search.html.twig', [
            'searchTerm' => $request->get('search'),
            'results' => $results
        ] );
    }
}