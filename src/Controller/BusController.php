<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

class BusController extends AbstractController
{
    /**
     * @Route("/bus", name="bus")
     */
    public function index()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://data.orleans-metropole.fr/api/records/1.0/search/?apikey=7c4e7563fc30cc228d982e4885d812cb7b22654d3539c190b016c972&dataset=tao_arrets_od&rows=1527');
        $content = $response->toArray();
        $infos = $content['records'];
        return $this->render('bus/index.html.twig', [
            'infos'=>$infos
        ]);
    }
}
