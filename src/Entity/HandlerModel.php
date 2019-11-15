<?php


namespace App\Entity;
use mysqli;

class HandlerModel
{
    public function addToken($idUser, $access, $refresh){
        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
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

}