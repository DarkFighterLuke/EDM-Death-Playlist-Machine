<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 14:03
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class HomeController extends AbstractController
{
    public function __construct()
    {
        session_start();
    }

    public function show()
    {
        //$this->get("twig")->addGlobal("status",0);
        return $this->render("home.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null]);
    }
}