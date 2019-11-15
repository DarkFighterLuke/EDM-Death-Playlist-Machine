<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 02/10/2019
 * Time: 13:09
 */

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use mysqli;


class SpotifyAuth extends AbstractController
{
    private $spotifyHandler;

    public function __construct(){
        session_start();
        $this->spotifyHandler=new Handler();
    }

    /**
     * @Route("/spotifyauth", name="spotifyauth")
     */
    public function send(){
        $this->spotifyHandler->auth();
    }

    /**
     * @Route("/spotifycallback")
     */
    public function receive(){
        $this->spotifyHandler->callback();
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(){
        $results=$this->spotifyHandler->searchHandler();
        $data[]=array();
        $i=0;
        foreach ($results->tracks->items as $item){
            $data[$i]["name"]=$item->name;
            $data[$i]["artist"]=$item->album->artists[0]->name;
            $data[$i]["uri"]=$item->uri;
            $i++;
        }

        return $this->render("addtrack.html.twig", ["user" => isset($_SESSION['user'])?$_SESSION['user']:null, "results" => $data]);
        //return $this->render("successful.html.twig", ["operation" => var_dump($data[0]), "link" => ""]);
    }

    /**
     * @Route("/addto", name="addto")
     */
    public function addTrack(){
        $idUser=$_SESSION['user']->idUser;
        $tracks=isset($_GET['choosen'])?$_GET['choosen']:array();
        $this->spotifyHandler->addTrackHandler($idUser, $tracks);
        return $this->render("addtrack.html.twig", ["user" => isset($_SESSION['user'])?$_SESSION['user']:null]);
    }


    public function parseInfoFromUri($trackUri){
        return $this->spotifyHandler->parseInfoFromUriHandler($trackUri);
    }
}