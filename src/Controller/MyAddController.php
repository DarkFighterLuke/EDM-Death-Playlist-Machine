<?php


namespace App\Controller;


use App\Entity\MyAddModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyAddController extends AbstractController
{
    private $model;
    private $week, $week1, $oldWeeks;

    public function __construct(){
        session_start();
        $this->model=new MyAddModel();
    }

    /**
     * @Route("/myadd", name="myadd")
     */
    public function __invoke(){
        $this->loadHistory();
        return $this->render("myadd.html.twig", ["user" => isset($_SESSION['user'])?$_SESSION['user']:null,
            "week" => $this->week,
            "week1" => $this->week1,
            "oldweeks" => $this->oldWeeks]);
    }

    public function loadHistory(){
        $idUser=$_SESSION["user"]->idUser;
        $date=date('Y-m-d h:i:s');

        $historyUris=$this->model->loadHistory($idUser, $date);
        $dataWeek=$historyUris[0];
        $dataWeek1=$historyUris[1];
        $dataOldWeeks=$historyUris[2];

        //$t=$_SESSION["api"]->getTrack($dataWeek1[0]["trackUri"]);
        //return $this->render("successful.html.twig", ["operation" => count($dataWeek1[0]), "link" => ""]);

        if(count($dataWeek[0])>0){
            $this->week[]=array();
            $i=0;
            foreach($dataWeek as $track){
                $row=$_SESSION["api"]->getTrack($track["trackUri"]);
                $this->week[$i]["name"]=$row->name;
                $this->week[$i]["artist"]=$row->album->artists[0]->name;;
                $this->week[$i]["uri"]=$row->uri;
                $this->week[$i]["duplicated"]=$track["duplicated"];
                $i++;
            }
        }

        if(count($dataWeek1[0])>0){
            $this->week1[]=array();
            $i=0;
            foreach($dataWeek1 as $track){
                $row=$_SESSION["api"]->getTrack($track["trackUri"]);
                $this->week1[$i]["name"]=$row->name;
                $this->week1[$i]["artist"]=$row->album->artists[0]->name;;
                $this->week1[$i]["uri"]=$row->uri;
                $this->week1[$i]["duplicated"]=$track["duplicated"];
                $i++;
            }
        }

        if(count($dataOldWeeks[0])>0){
            $this->oldWeeks[]=array();
            $i=0;
            foreach($dataOldWeeks as $track){
                $row=$_SESSION["api"]->getTrack($track["trackUri"]);
                $this->oldWeeks[$i]["name"]=$row->name;
                $this->oldWeeks[$i]["artist"]=$row->album->artists[0]->name;;
                $this->oldWeeks[$i]["uri"]=$row->uri;
                $this->oldWeeks[$i]["duplicated"]=$track["duplicated"];
                $i++;
            }
        }
    }
}