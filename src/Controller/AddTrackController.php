<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 15:35
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AddTrackController extends AbstractController
{
    public function __construct(){
        session_start();
    }

    /**
     * @Route("/addtrack", name="addtrack")
     */
    public function __invoke(){
        return $this->render("add_track.html.twig", ["user" => isset($_SESSION['user'])?$_SESSION['user']:null]);
    }

}