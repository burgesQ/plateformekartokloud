<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Playlist;
use App\Entity\Artist;
use App\Entity\Track;

/**
 * @Route("/view")
 */
class ViewController extends Controller
{
    private $arrayClass = [
        'map'   => [ 'route' => 'api/map.html.twig' ],
    ];

    /**
     * @Route(
     *     "/{component}",
     *     requirements={
     *         "class"="map",
     *         "class"="company/create"
     *     }
     * )
     *
     * @param string $component
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function byClassAction(string $component): Response
    {
        return $this->render($this->arrayClass[$component]['route'], []);
    }
}