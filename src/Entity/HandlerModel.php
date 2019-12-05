<?php


namespace App\Entity;
use mysqli;

class HandlerModel
{
    private $masterId=9;

    public function addToken($idUser, $access, $refresh){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $statement=$db->prepare("UPDATE user SET accessToken=?, refreshToken=? WHERE idUSer=?");
        $statement->bind_param("ssi",$access,$refresh, $idUser);
        $result=$statement->execute();
        if(!$result){
            $db->errno("Errore token");
            $db->close();
            return false;
        }
        else{
            $db->close();
            return true;
        }
    }

    public function updatePlaylist(){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT accessToken, refreshToken FROM user WHERE idUser=$this->masterId");
        $masterTokens=array();
        if($res){
            while($row = $res->fetch_assoc()){
                $masterTokens["accessToken"]=$row["accessToken"];
                $masterTokens["refreshToken"]=$row["refreshToken"];
            }
            $data["masterTokens"]=$masterTokens;
        }

        $res=$db->query("SELECT * FROM added_tracks WHERE duplicated IS NULL AND removed IS NULL AND datetime between date_sub(now(), INTERVAL 7 DAY) AND now() ORDER BY datetime DESC");
        if($res){
            $i=0;
            while($row = $res->fetch_assoc()){
                $tracks[$i]["trackUri"]=$row["trackUri"];
                $i++;
            }
            $data["tracks"]=isset($tracks)?$tracks:array();
        }

        return $data;
    }

    public function isDuplicated($trackUri){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT * FROM added_tracks WHERE trackUri='$trackUri' AND removed IS NULL")->num_rows;
        if($res>0){
            return true;
        }
        else{
            return false;
        }
    }

    public function removeTrack($trackUri){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $statement=$db->prepare("UPDATE added_tracks SET removed=1 WHERE trackUri=?");
        $statement->bind_param("s", $trackUri);
        $res=$statement->execute();
        if($res){
            return true;
        }
        else{
            return false;
        }
    }

    public function checkWeeksAdds($idUser){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT * FROM `added_tracks` WHERE idUser=$idUser AND datetime BETWEEN LastSaturday() AND now() AND duplicated IS NULL AND removed IS NULL")->num_rows;

        return $res;
    }

    public function postedBy($uri){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT username FROM user INNER JOIN added_tracks ON(user.idUser=added_tracks.idUser) WHERE removed IS NULL AND duplicated IS NULL AND trackUri='$uri'");
        $row=$res->fetch_assoc();
        return $row["username"];
    }

}