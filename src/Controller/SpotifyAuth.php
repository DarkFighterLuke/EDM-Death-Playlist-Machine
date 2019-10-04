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
    private $api;

    public function __construct(){
        session_start();
    }

    /**
     * @Route("/spotifyauth", name="spotifyauth")
     */
    public function auth(){
        $session = new SpotifyWebAPI\Session(
            '2bc6c6e3a2424d2b9fb8c22462d16e0b',
            'fc8eee9ae9364d518d27c30f5beb7b36',
            'REDIRECT_URI'
        );

        $options = [
            'scope' => [
                'playlist-read-collaborative',
                'playlist-modify-private',
                'playlist-modify-public',
                'playlist-read-private',

                'user-modify-playback-state',
                'user-read-currently-playing',
                'user-read-playback-state',

                'user-read-private',
                'user-read-email',

                'user-library-modify',
                'user-library-read',

                'user-follow-modify',
                'user-follow-read',

                'user-read-recently-played',
                'user-top-read',

                'streaming',
                'app-remote-control'
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
            '2bc6c6e3a2424d2b9fb8c22462d16e0b',
            'fc8eee9ae9364d518d27c30f5beb7b36',
            'REDIRECT_URI'
        );

// Request a access token using the code from Spotify
        $session->requestAccessToken($_GET['code']);

        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();

        $_SESSION['user']['accessToken']=$accessToken;
        $_SESSION['user']['refreshToken']=$refreshToken;

        $success=addToken($accessToken,$refreshToken);

// Send the user along and fetch some data!
        if($success){
            checkScopes($session);
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
            $db->close();
            return false;
        }
        else{
            $db->close();
            return true;
        }
    }

    function checkScopes($session){
        $scope=$session->getScope();
        //check scope and then:
        $this->api=new SpotifyWebAPI\SpotifyWebAPI();
        $this->api->setAccessToken($session->getAccessToken());
        header("Location: ");
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(){
        $query=$_GET['query'];
        $results=$this->api->search($query,"track");
        $this->render("addtrack.html.twig", ["user" => isset($_SESSION['user'])?$_SESSION['user']:null], ["results" => $results]);
    }

    /**
     * @Route("/addto", name="addto")
     */
    public function addTo(){
        $idUser=$_SESSION['user']['idUser'];
        $tracks=$_GET['choosen'];
        $date=date('Y-m-d h:i:s');

        $db=new mysqli("localhost","root","","my_edmdeathplaylistmachine");
        foreach($tracks as $track){
            $res=$db->query("INSERT INTO added_tracks(idUser,track,datetime) VALUES('$idUser','$track',$date)");
            if(!$res){
                echo "Errore Aggiunta";
            }
        }
        $db->close();
        header("Location: /addtrack");
    }
}