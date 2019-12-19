<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://data.orleans-metropole.fr/api/records/1.0/search/?apikey=7c4e7563fc30cc228d982e4885d812cb7b22654d3539c190b016c972&dataset=air-quality&lang=Fr&sort=measurements_value&facet=location&facet=measurements_parameter&facet=measurements_sourcename&facet=measurements_lastupdated');
        $content = $response->toArray();
        $date = new \DateTime();
        $day = date_format($date, 'Y-m');
        $AQI = 0;
        foreach ($content['records'] as $key => $records) {
            if (substr($records['fields']['measurements_lastupdated'], 0, 7) === $day && $AQI < $records['fields']['measurements_value']) {
                $AQI = $records['fields']['measurements_value'];
            }
        }

        if ($AQI <= 50) {
            $image = 'https://cdn2.iconfinder.com/data/icons/starwars/icons/128/clone-1.png';
            $index = 'Bonne';
            $pollutionMessage = "Niveau de pollution faible ;)";
        } elseif (50 < $AQI && $AQI <= 100) {
            $image = 'https://cdn2.iconfinder.com/data/icons/starwars/icons/128/clone-4.png';
            $index = 'Modérée';
            $pollutionMessage = "Niveau de pollution modérée :)";
        } elseif (100 < $AQI && $AQI <= 150) {
            $image = 'https://cdn2.iconfinder.com/data/icons/starwars/icons/128/clone-2.png';
            $index = 'élevée';
            $pollutionMessage = "Niveau de pollution élevée !";
        } else {
            $image = 'https://cdn2.iconfinder.com/data/icons/starwars/icons/128/clone-3.png';
            $index = 'Dangereuse';
            $pollutionMessage = "Niveau de pollution trop élevée !";
        }


        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.meteo-concept.com/api/forecast/nextHours/?token=c2de9bb2de53a2e3d96d8e35a6437939d959cf9b19de03557f4ae8505098a35a&insee=45234');
        $content = $response->toArray();
        $probaPluie = $content['forecast'][0]['probarain'];

        if ($probaPluie < 50) {
            $meteoMessage = "Il fait beau ;))";
        } else {
            $meteoMessage = "Il va pleuvoir :((";
        }

        if ($probaPluie < 50 && ($index == 'Bonne' || $index == 'Modérée')) {
            $recommendation = 'Privilégies le velo ou la marche !';
        } else {
            $recommendation = 'Privilégies le bus ou le tram !';
        }

        return $this->render('home/index.html.twig', [
            "AQI" => $AQI,
            "image" => $image,
            "index" => $index,
            "pollutionMessage" => $pollutionMessage,
            "meteoMessage" => $meteoMessage,
            "recommendation" => $recommendation,
        ]);
    }
}
