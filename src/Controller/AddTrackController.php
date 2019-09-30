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
    /**
     * @Route("/addtrack", name="addtrack")
     */
    public function __invoke(){
        return $this->render("add_track.html.twig");
    }
}