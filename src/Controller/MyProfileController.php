<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 17:51
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyProfileController extends AbstractController
{

    public function __construct(){
        session_start();
    }

    /**
     * @Route("/profile", name="myprofile")
     */
    public function show(){
        return $this->render("myprofile.html.twig", ["user" => $_SESSION['user']]);
    }


}