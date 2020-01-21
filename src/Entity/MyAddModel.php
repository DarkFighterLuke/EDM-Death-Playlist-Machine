<?php


namespace App\Entity;

use mysqli;

class MyAddModel
{
    public function loadHistory($idUser, $date){
        $week[]=array();
        $week1[]=array();
        $oldweeks[]=array();
        $db=new mysqli("localhost","hnlzewad_root","","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT * FROM `added_tracks` WHERE datetime between LastSaturday() AND now() AND idUser=$idUser AND removed IS NULL ORDER BY datetime DESC");
        if($res){
            $i=0;
            while($row=$res->fetch_assoc()){
                $week[$i]["trackUri"]=$row["trackUri"];
                $week[$i]["datetime"]=$row["datetime"];
                $week[$i]["duplicated"]=$row["duplicated"];
                $i++;
            }
        }

        $res=$db->query("SELECT * FROM `added_tracks` WHERE datetime between date_sub(LastSaturday(), INTERVAL 7 day) and LastSaturday() AND idUser=$idUser AND removed IS NULL ORDER BY datetime DESC");
        if($res){
            $i=0;
            while($row=$res->fetch_assoc()){
                $week1[$i]["trackUri"]=$row["trackUri"];
                $week1[$i]["datetime"]=$row["datetime"];
                $week1[$i]["duplicated"]=$row["duplicated"];
                $i++;
            }
        }

        $res=$db->query("SELECT * FROM `added_tracks` WHERE datetime < date_sub(LastSaturday(), INTERVAL 7 day) AND idUser=$idUser AND removed IS NULL ORDER BY datetime DESC");
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
