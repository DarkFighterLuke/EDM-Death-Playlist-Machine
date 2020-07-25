<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyTracksController extends AbstractController
{

    public function __invoke()
    {

        return $this->render("home.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null, "results" => null]);
    }
}