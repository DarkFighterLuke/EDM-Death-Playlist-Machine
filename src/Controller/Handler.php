<?php

namespace App\Controller;
use App\Entity\HandlerModel;
use SpotifyWebAPI;
use mysqli;

class Handler{

    private const playlistID="2X5H6OzOknmksRF2Eb1xlq";
    public $maxWeeklyAdds=5;

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
        header("Location: https://edm-death-playlist-machine.netsons.org/public/index.php/");
    }

    public function loginSetSession($accessToken, $refreshToken){
        $session = new SpotifyWebAPI\Session(
            '2bc6c6e3a2424d2b9fb8c22462d16e0b',
            'fc8eee9ae9364d518d27c30f5beb7b36',
            'https://edm-death-playlist-machine.netsons.org/public/index.php/spotifycallback'
        );
        $session->setAccessToken($accessToken);
        $session->setRefreshToken($refreshToken);

        $session->refreshAccessToken($refreshToken);

        return $session;
    }

    public function searchHandler(){
        $query=$_GET['query'];
        $query=str_replace("+", " ", $query);
        $results=$_SESSION["api"]->search($query,"track",null);
        return $results;
    }

    public function isDuplicated($trackUri){
        return $this->model->isDuplicated($trackUri);
    }

    public function addTokenHandler($access,$refresh){
        $idUser=$_SESSION['user']->idUser;
        return $this->model->addToken($idUser, $access, $refresh);
    }

    public function addTrackHandler($idUser, $tracks){
        $date=date('Y-m-d h:i:s');
        //return $this->checkWeeksAdds($idUser);
        if($this->checkWeeksAdds($idUser)+count($tracks)>$this->maxWeeklyAdds){
            return false;
        }
        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
        foreach($tracks as $track){
            $res=$db->query("SELECT * FROM added_tracks WHERE duplicated IS NULL AND removed IS NULL AND trackUri='$track'")->num_rows;
            if($res>0){
                $statement=$db->prepare("INSERT INTO added_tracks(idUser, trackUri, datetime, duplicated) VALUES(?, ?, now(), 1)");
            }
            else{
                $statement=$db->prepare("INSERT INTO added_tracks(idUser,trackUri,datetime) VALUES(?, ?, now())");
            }
            $statement->bind_param("is", $idUser, $track);
            $statement->execute();
        }

        $db->close();
        return true;
    }

    public function parseInfoFromUriHandler($trackUri){
        return $this->api->getTrack($trackUri);
    }

    public function checkWeeksAdds($idUser){
        return $this->model->checkWeeksAdds($idUser);
    }

    /*public function updatePlaylistHandler(){
        $db=new mysqli("localhost","hnlzewad_root","3cvS#WZ]lkYw","hnlzewad_edmdeathplaylistmachine");
        $res=$db->query("SELECT idUser, accessToken, refreshToken FROM user");
        if($res){
            while($row = $res->fetch_assoc()){
                $i=$row["idUser"];
                $tokens[$i]["aToken"]=$row["accessToken"];
                $tokens[$i]["rToken"]=$row["refreshToken"];
            }

            $res=$db->query("SELECT * FROM added_tracks WHERE duplicated IS NULL AND datetime between date_sub(curdate(), INTERVAL 7 DAY) AND curdate() ORDER BY datetime DESC");
            if($res){
                $tempapi=new SpotifyWebAPI\SpotifyWebAPI();
                while($row = $res->fetch_assoc()){
                    $i=$row["idUser"];
                    $session=$this->loginSetSession($tokens[$i]["aToken"], $tokens[$i]["rToken"]);
                    $tempapi->setSession($session);
                    $tempapi->setOptions([
                        'auto_refresh' => true,
                    ]);
                    try{
                        $tempapi->addPlaylistTracks("3XLNVnS9qgAVhCEtVQXxPH", $row["trackUri"]);
                    }
                    catch(\Exception $e){
                        $session->refreshAccessToken($tokens[$i]["rToken"]);
                        $session->getAccessToken();
                        $tempapi->setSession($session);
                        $tempapi->setOptions([
                            'auto_refresh' => true,
                        ]);
                        $tempapi->addPlaylistTracks("3XLNVnS9qgAVhCEtVQXxPH", $row["trackUri"]);
                    }
                }
            }
        }
    }*/

    public function createMasterTempAPI(){
        $data=$this->model->updatePlaylist();
        $tempapi=new SpotifyWebAPI\SpotifyWebAPI();
        $session=$this->loginSetSession($data["masterTokens"]["accessToken"], $data["masterTokens"]["refreshToken"]);
        $tempapi->setSession($session);
        $tempapi->setOptions([
            'auto_refresh' => true,
        ]);
        return $tempapi;
    }

    public function updatePlaylistHandler(){
        $data=$this->model->updatePlaylist();
        $tempapi=$this->createMasterTempAPI();
        try{
            if($data["tracks"]){
                $i=0;
                while($i<count($data["tracks"])){
                    $tempapi->addPlaylistTracks(self::playlistID, $data["tracks"][$i]["trackUri"], ["position" => 0]);
                    $i++;
                }
            }
            return true;
        }
        catch(\Exception $e){
            echo $e;
            return false;
        }
    }

    public function removeTrackHandler($trackUri, $tracks){
        $tempapi = $this->createMasterTempAPI();
        $tempapi->deletePlaylistTracks(self::playlistID, $tracks);
        foreach($trackUri as $uri){
            $success=$this->model->removeTrack($uri);
        }
    }

    public function myTracksHandler(){
        $tempapi=new SpotifyWebAPI\SpotifyWebAPI();
        $session=$this->loginSetSession($_SESSION["user"]->accessToken, $_SESSION["user"]->refreshToken);
        $tempapi->setSession($session);
        $library=$tempapi->getMySavedTracks(['limit' => 20]);
        $i=0;
        foreach($library->items as $item){
            $tracks[$i]["name"]=$item->track->name;
            $tracks[$i]["artist"]=$item->track->artists[0]->name;;
            $tracks[$i]["uri"]=$item->track->uri;
            $tracks[$i]["duplicated"]=$this->model->isDuplicated($item->track->uri);
            $i++;
        }
        return isset($tracks)?$tracks:null;
    }

    public function showOfficialPlaylistHandler(){
        $tempapi=$this->createMasterTempAPI();
        $songs=$tempapi->getPlaylistTracks(self::playlistID);
        $i=0;
        foreach($songs->items as $item){
            $tracks[$i]["name"]=$item->track->name;
            $tracks[$i]["artist"]=$item->track->artists[0]->name;;
            $tracks[$i]["uri"]=$item->track->uri;
            $tracks[$i]["album"]=$item->track->album->name;
            $tracks[$i]["postedBy"]=$this->model->postedBy($item->track->uri);
            $i++;
        }
        return isset($tracks)?$tracks:null;
    }
}
?>
