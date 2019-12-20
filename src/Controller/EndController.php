<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Annotation\Route;

class EndController extends AbstractController
{
    /**
     * @Route("/end", name="end")
     */
    public function index()
    {

        return $this->render('end/index.html.twig');
    }
}
