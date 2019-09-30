<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 24/09/2019
 * Time: 17:33
 */

namespace App\Entity;


class User
{
    public $idUser, $username, $password, $email, $accessToken, $refreshToken, $admin;

    public function __construct($idUser, $username, $password, $email, $accessToken, $refreshToken, $admin){
        $this->userId=$idUser;
        $this->username=$username;
        $this->password=$password;
        $this->email=$email;
        $this->accessToken=$accessToken;
        $this->refreshToken=$refreshToken;
        $this->admin=$admin;
    }

    public function getIdUser(){
        return $this->idUser;
    }
}