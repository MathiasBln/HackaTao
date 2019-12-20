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
            $color = '#019865';
            $index = 'Bonne';
            $pollutionMessage = "Niveau de pollution faible ;)";
        } elseif (50 < $AQI && $AQI <= 100) {
            $color = '#FCDF30';
            $index = 'Modérée';
            $pollutionMessage = "Niveau de pollution modérée :)";
        } elseif (100 < $AQI && $AQI <= 150) {
            $color = '#F89839';
            $index = 'élevée';
            $pollutionMessage = "Niveau de pollution élevée !";
        } else {
            $color = '#CE1F37';
            $index = 'Dangereuse';
            $pollutionMessage = "Niveau de pollution trop élevée !";
        }


        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.meteo-concept.com/api/forecast/nextHours/?token=c2de9bb2de53a2e3d96d8e35a6437939d959cf9b19de03557f4ae8505098a35a&insee=45234');
        $content = $response->toArray();
        $probaPluie = $content['forecast'][0]['probarain'];


        $meteoMessagesBeau = ["Il fait beau ;)", "Beau aujourd'hui il fait", "Mais quel beau temps !"];
        $meteoMessagesMoche = ["Il va pleuvoir :(", "Moche il fait aujourd'hui", "Sort la parka" , "brrbrbrrbrrrbrrr"];
        $recommendations = ['Privilégies le velo ou la marche !', "Le vélo, la marche ou Jabba the Hutt !", ];

        if ($probaPluie < 50) {
            $meteoMessage = $meteoMessagesBeau[array_rand($meteoMessagesBeau)];
        } else {
            $meteoMessage = $meteoMessagesMoche[array_rand($meteoMessagesMoche)];
        }

        if ($probaPluie < 50 && ($index == 'Bonne' || $index == 'Modérée')) {
            $recommendation = $recommendations[array_rand($recommendations)];
        } else {
            $recommendation = 'Privilégies le bus ou le tram !';
        }

        return $this->render('home/index.html.twig', [
            "AQI" => $AQI,
            "color" => $color,
            "index" => $index,
            "pollutionMessage" => $pollutionMessage,
            "meteoMessage" => $meteoMessage,
            "recommendation" => $recommendation,
        ]);
    }
}
