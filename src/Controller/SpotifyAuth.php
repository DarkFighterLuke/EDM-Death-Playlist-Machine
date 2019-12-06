<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 02/10/2019
 * Time: 13:09
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use mysqli;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Json;

class SpotifyAuth extends AbstractController
{
    private $spotifyHandler;
    public $maxWeeklyAdds = 5;

    public function __construct()
    {
        session_start();
        $this->spotifyHandler = new Handler();
    }

    /**
     * @Route("/spotifyauth", name="spotifyauth")
     */
    public function send()
    {
        $this->spotifyHandler->auth();
    }

    /**
     * @Route("/spotifycallback")
     */
    public function receive()
    {
        $this->spotifyHandler->callback();
    }

    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        $results = $this->spotifyHandler->searchHandler();
        $data[] = array();
        if ($results->tracks->total > 0) {
            $i = 0;
            foreach ($results->tracks->items as $item) {
                $data[$i]["name"] = $item->name;
                $data[$i]["artist"] = $item->album->artists[0]->name;
                $data[$i]["uri"] = $item->uri;
                $data[$i]["duplicated"] = $this->spotifyHandler->isDuplicated($item->uri);
                $i++;
            }
            return $this->render("addtrack.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null, "results" => $data]);
        } else {
            echo("ok");
            return $this->render("addtrack.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null, "found" => true]);
        }
    }

    /**
     * @Route("/addto", name="addto")
     */
    public function addTrack()
    {
        $idUser = $_SESSION['user']->idUser;
        $tracks = isset($_GET['choosen']) ? $_GET['choosen'] : array();
        //return $this->render("errorbase.html.twig", ["message" => $this->spotifyHandler->addTrackHandler($idUser, $tracks)]);
        if (count($tracks) > $this->maxWeeklyAdds) {
            return $this->render("errorbase.html.twig", ["message" => "Too many tracks."]);
        }
        if (!$this->spotifyHandler->addTrackHandler($idUser, $tracks)) {
            return $this->render("errorbase.html.twig", ["message" => "Max number of Weekly adds reached."]);
        } else {
            return $this->redirectToRoute("myadd");
        }
    }

    public function parseInfoFromUri($trackUri)
    {
        return $this->spotifyHandler->parseInfoFromUriHandler($trackUri);
    }

    /**
     * @Route("/updateplaylist")
     */
    public function updatePlaylist()
    {
        $timerUpdate = new timerUpdate();
        //return $this->render("errorbase.html.twig", ["message" => $timerUpdate->timer()]);
        if ($timerUpdate->timer()) {
            $success = $this->spotifyHandler->updatePlaylistHandler();
        } else {
            $success = false;
        }
        if ($success) {
            return $this->render("successful.html.twig", ["operation" => "Playlist Update", "link" => ""]);
        } else {
            return $this->render("errorbase.html.twig", ["message" => "Playlist Update failed."]);
        }
    }

    /**
     * @Route("/removetrack", name="removetrack")
     */
    public function removeTrack()
    {
        $trackUri = isset($_POST["choosen"]) ? $_POST["choosen"] : array();
        $i = 0;
        foreach ($trackUri as $uri) {
            $tracks["tracks"][$i]["id"] = $uri;
            $i++;
        }
        $this->spotifyHandler->removeTrackHandler($trackUri, $tracks);
        return $this->redirect("https://edm-death-playlist-machine.netsons.org/public/index.php/myadd");
    }

    /**
     * @Route("/checkWeekAdds", name="checkWeekAdds")
     */
    public function checkWeeksAdds()
    {
        return new JsonResponse(["adds" => $this->spotifyHandler->checkWeeksAdds($_SESSION['user']->idUser)]);
    }

    /**
     * @Route("/mytracks", name="mytracks")
     */
    public function myTracks()
    {
        $tracks = $this->spotifyHandler->myTracksHandler();
        return $this->render("mytracks.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null, "results" => $tracks]);
    }

    /**
     * @Route("/", name="home")
     */
    public function showOfficialPlaylistHome()
    {
        $tracks = $this->spotifyHandler->showOfficialPlaylistHandler();
        return $this->render("home.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null, "results" => $tracks]);
    }

    /**
     * @Route("/login", name="login", methods="GET")
     */
    public function showOfficialPlaylistLogin()
    {
        $tracks = $this->spotifyHandler->showOfficialPlaylistHandler();
        return $this->render("login.html.twig", ["user" => isset($_SESSION['user']) ? $_SESSION['user'] : null, "results" => $tracks]);
    }
}