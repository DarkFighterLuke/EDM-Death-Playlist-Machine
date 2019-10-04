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
use SpotifyWebAPI;

class SpotifyAuth extends AbstractController
{
    /**
     * @Route("/spotifyauth")
     */
    public function auth(){
        $session = new SpotifyWebAPI\Session(
            'CLIENT_ID',
            'CLIENT_SECRET',
            'REDIRECT_URI'
        );

        $options = [
            'scope' => [
                'playlist-read-private',
                'user-read-private',
            ],
        ];

        header('Location: ' . $session->getAuthorizeUrl($options));
        die();
    }

    /**
     * @Route("/spotifycallback")
     */
    public function callback(){
        $session = new SpotifyWebAPI\Session(
            'CLIENT_ID',
            'CLIENT_SECRET',
            'REDIRECT_URI'
        );

// Request a access token using the code from Spotify
        $session->requestAccessToken($_GET['code']);

        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();

        $success=addToken($accessToken,$refreshToken);

// Send the user along and fetch some data!
        if($success){
            header('Location: /');
        }
        else{
            echo "<h1>Errore token</h1>";
        }
        die();
    }

    function addToken($access,$refresh){
        $idUser=$_SESSION['user']['idUser'];
        $db=new mysqli("localhost","root","","my_edmdeathplaylistmachine");
        $statement=$db->prepare("UPDATE user SET accessToken='?', refreshToken='?' WHERE idUSer=?");
        $statement->bind_param("ssi",$access,$refresh,$idUser);
        $result=$statement->execute();
        if(!$result){
            $db->errno("Errore token");
            return false;
        }
        else{return true;}
    }
}