<?php


namespace App\Entity;

use mysqli;

class MyAddModel
{
    public function loadHistory($idUser, $date){
        $week[]=array();
        $week1[]=array();
        $oldweeks[]=array();
        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT * FROM `added_tracks` WHERE datetime between date_sub(now(), INTERVAL 7 DAY) and now() AND idUser=$idUser AND removed IS NULL ORDER BY datetime DESC");
        if($res){
            $i=0;
            while($row=$res->fetch_assoc()){
                $week[$i]["trackUri"]=$row["trackUri"];
                $week[$i]["datetime"]=$row["datetime"];
                $week[$i]["duplicated"]=$row["duplicated"];
                $i++;
            }
        }

        $res=$db->query("SELECT * FROM `added_tracks` WHERE datetime between date_sub(now(), INTERVAL 14 DAY) and date_sub(now(), INTERVAL 7 DAY) AND idUser=$idUser AND removed IS NULL ORDER BY datetime DESC");
        if($res){
            $i=0;
            while($row=$res->fetch_assoc()){
                $week1[$i]["trackUri"]=$row["trackUri"];
                $week1[$i]["datetime"]=$row["datetime"];
                $week1[$i]["duplicated"]=$row["duplicated"];
                $i++;
            }
        }

        $res=$db->query("SELECT * FROM `added_tracks` WHERE datetime < date_sub(now(), INTERVAL 14 DAY) AND idUser=$idUser AND removed IS NULL ORDER BY datetime DESC");
        if($res){
            $i=0;
            while($row=$res->fetch_assoc()){
                $oldweeks[$i]["trackUri"]=$row["trackUri"];
                $oldweeks[$i]["datetime"]=$row["datetime"];
                $oldweeks[$i]["duplicated"]=$row["duplicated"];
                $i++;
            }
        }

        $db->close();
        return [$week, $week1, $oldweeks];
    }
}