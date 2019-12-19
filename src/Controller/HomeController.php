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
        $response = $client->request('GET', 'https://data.orleans-metropole.fr/api/records/1.0/search/?dataset=air-quality&lang=Fr&sort=measurements_value&facet=location&facet=measurements_parameter&facet=measurements_sourcename&facet=measurements_lastupdated');
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
        } elseif (50 < $AQI && $AQI <= 100) {
            $color = '#FCDF30';
            $index = 'Modérée';
        } elseif (100 < $AQI && $AQI <= 150) {
            $color = '#F89839';
            $index = 'élevée';
        } else {
            $color = '#CE1F37';
            $index = 'Dangereuse';
        }



        return $this->render('home/index.html.twig', [
            "AQI" => $AQI,
            "color" => $color,
            "index" => $index,
        ]);
    }
}
