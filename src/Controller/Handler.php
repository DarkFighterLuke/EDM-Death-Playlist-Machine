<?php

namespace App\Controller;
use App\Entity\HandlerModel;
use SpotifyWebAPI;
use mysqli;

class Handler{

    private $api;
    private $model;

    public function __construct()
    {
        //session_start();
        $this->model=new HandlerModel();
    }

    public function auth(){
        $session = new SpotifyWebAPI\Session(
            '2bc6c6e3a2424d2b9fb8c22462d16e0b',
            'fc8eee9ae9364d518d27c30f5beb7b36',
            'https://edm-death-playlist-machine.netsons.org/public/index.php/spotifycallback'
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

    public function callback(){
        $session = new SpotifyWebAPI\Session(
            '2bc6c6e3a2424d2b9fb8c22462d16e0b',
            'fc8eee9ae9364d518d27c30f5beb7b36',
            'https://edm-death-playlist-machine.netsons.org/public/index.php/spotifycallback'
        );



// Request a access token using the code from Spotify
        $session->requestAccessToken($_GET['code']);

        $this->api=new SpotifyWebAPI\SpotifyWebAPI();
        $this->api->setSession($session);
        $this->api->setOptions([
            'auto_refresh' => true,
        ]);
        $newAccessToken = $session->getAccessToken();
        $newRefreshToken = $session->getRefreshToken();

        $_SESSION['user']->accessToken=$newAccessToken;
        $_SESSION['user']->refreshToken=$newRefreshToken;


        $success=$this->addTokenHandler($newAccessToken,$newRefreshToken);


// Send the user along and fetch some data!
        if($success){
            $this->checkScopes($session);
        }
        else{
            echo "<h1>Errore token</h1>";
        }
        die();
    }

    private function checkScopes($session){
        $scope=$session->getScope();
        //check scope and then:
        $this->api->setAccessToken($session->getAccessToken());
        $_SESSION["api"]=$this->api;
        header("Location: https://edm-death-playlist-machine.netsons.org/public/index.php/profile");
    }

    public function loginSetSession($accessToken, $refreshToken){
        $session = new SpotifyWebAPI\Session(
            '2bc6c6e3a2424d2b9fb8c22462d16e0b',
            'fc8eee9ae9364d518d27c30f5beb7b36',
            'https://edm-death-playlist-machine.netsons.org/public/index.php/spotifycallback'
        );
        $session->setAccessToken($accessToken);
        $session->setRefreshToken($refreshToken);

        $this->api=new SpotifyWebAPI\SpotifyWebAPI();
        $this->api->setSession($session);
        $this->api->setOptions([
            'auto_refresh' => true,
        ]);
        $_SESSION["api"]=$this->api;
    }

    public function searchHandler(){
        $query=$_GET['query'];
        $query=str_replace("+", " ", $query);
        $results=$_SESSION["api"]->search($query,"track",null);
        return $results;
    }

    public function addTokenHandler($access,$refresh){
        $idUser=$_SESSION['user']->idUser;
        return $this->model->addToken($idUser, $access, $refresh);
    }

    public function addTrackHandler($idUser, $tracks){
        $date=date('Y-m-d h:i:s');

        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
        foreach($tracks as $track){
            $res=$db->query("SELECT * FROM added_tracks WHERE duplicated IS NULL AND trackUri='$track'")->num_rows;
            if($res>0){
                $statement=$db->prepare("INSERT INTO added_tracks(idUser, trackUri, datetime, duplicated) VALUES(?, ?, '$date', 1)");
            }
            else{
                $statement=$db->prepare("INSERT INTO added_tracks(idUser,trackUri,datetime) VALUES(?, ?, '$date')");
            }
            $statement->bind_param("is", $idUser, $track);
            $statement->execute();
        }

        $db->close();
    }

    public function parseInfoFromUriHandler($trackUri){
        return $this->api->getTrack($trackUri);
    }
}
?>
