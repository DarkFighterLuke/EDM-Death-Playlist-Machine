<?php


namespace App\Entity;

use mysqli;

class SignUpModel
{
    public function register($username, $password, $countryCode, $telephone){
        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
        $statement=$db->prepare("SELECT username FROM user WHERE username=?");
        $statement=$db->prepare("SELECT username FROM user WHERE username=?");
        $statement->bind_param("s",$username);
        $statement->execute();
        $result=$statement->get_result();
        if($result->num_rows>0) {
            $db->close();
            return true;
        }
        else {
            $statement = $db->prepare("INSERT INTO user (username,password,countryCode,telephone,admin) VALUES(?,AES_ENCRYPT(?,'chiavetemporanea'),?,?,0)");
            $statement->bind_param("ssss", $username, $password, $countryCode, $telephone);
            $statement->execute();
            $result = $statement->get_result();
            $db->close();
            return false;
        }
    }

}