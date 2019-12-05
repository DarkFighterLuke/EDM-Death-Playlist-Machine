<?php


namespace App\Entity;


use App\Controller\Handler;
use mysqli;

class LoginModel
{
    public function checkLogin($username, $password){
        $db=new mysqli("localhost","hnlzewad_root","FerrariVSPasquini:db","hnlzewad_edmdeathplaylistmachine");
        $statement=$db->prepare("SELECT * FROM user WHERE username=? AND password=AES_ENCRYPT(?,'chiavetemporanea')");
        $statement->bind_param("ss",$username,$password);
        $statement->execute();
        $result=$statement->get_result();
        if($result->num_rows>0) {
            while ($row = $result->fetch_assoc()) {
                $accessToken = $row['accessToken'];
                $refreshToken = $row['refreshToken'];
                $this->user = new User($row['idUser'], $row['username'], $row['password'], $accessToken, $refreshToken, $row['admin']);
                $_SESSION['user'] = $this->user;
                $spotifyHandler = new Handler();
                $spotifyHandler->auth();
                //echo $_SESSION['user'];
                $db->close();
                return true;
            }
        }
        else{
            $db->close();
            return false;
        }
    }

}