<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

class VeloController extends AbstractController
{
    /**
     * @Route("/velo", name="velo")
     */
    public function index()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://data.orleans-metropole.fr/api/records/1.0/search/?apikey=7c4e7563fc30cc228d982e4885d812cb7b22654d3539c190b016c972&dataset=liste-des-stations-velo-2018-orleans-metropole');
        $content = $response->toArray();
        $infos = $content['records'];
        return $this->render('velo/index.html.twig', [
            'infos'=>$infos
        ]);
    }
}
