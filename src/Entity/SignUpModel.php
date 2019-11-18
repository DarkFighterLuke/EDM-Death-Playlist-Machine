<?php


namespace App\Entity;

use mysqli;

class SignUpModel
{
    public function register($username, $password, $email, $countryCode, $telephone){
        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
        $statement=$db->prepare("SELECT username,email FROM user WHERE username=? OR email=?");
        $statement=$db->prepare("SELECT username,email FROM user WHERE username=? OR email=?");
        $statement->bind_param("ss",$username,$email);
        $statement->execute();
        $result=$statement->get_result();
        if($result->num_rows>0) {
            $db->close();
            return true;
        }
        else {
            $statement = $db->prepare("INSERT INTO user (username,password,email,countryCode,telephone,admin) VALUES(?,AES_ENCRYPT(?,'chiavetemporanea'),?,?,?,0)");
            $statement->bind_param("sssss", $username, $password, $email, $countryCode, $telephone);
            $statement->execute();
            $result = $statement->get_result();
            $db->close();
            return false;
        }
    }

}