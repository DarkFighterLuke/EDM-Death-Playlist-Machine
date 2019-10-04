<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 16:08
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\Session\Session;
//use Symfony\Component\HttpFoundation\Request;
use mysqli;

class LoginController extends AbstractController
{
    private $user;

    public function __construct(){
        session_start();
    }

    /**
     * @Route("/login", name="login", methods="GET")
     */
    public function show(){
        //$_SESSION['user']=$this->user;
        //echo $_SESSION['user'];

        return $this->render("login.html.twig",["user" => isset($_SESSION['user'])?$_SESSION['user']:null]);
    }

    /**
     * @Route("/login", methods="POST")
     */
    public function checkLogin(){
        $username=$_POST['username'];
        $password=$_POST['password'];

        //session_start();
        $db=new mysqli("localhost","root","","my_edmdeathplaylistmachine");
        $statement=$db->prepare("SELECT * FROM user WHERE username=? OR email=? AND password=AES_DECRYPT(?,'chiavetemporanea')");
        $statement->bind_param("sss",$username,$username,$password);
        $statement->execute();
        $result=$statement->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                /*$session=$request->getSession();
                $session->start();
                $session->set("idUser",$row['idUser']);
                $session->set("username",$row['username']);
                $session->set("password",$row['password']);
                $session->set("email",$row['email']);
                $session->set("accessToken",$row['accessToken']);
                $session->set("refreshToken",$row['refreshToken']);
                $session->set("status",1);
                $session->set("admin",$row['admin']);*/
                $this->user=new User($row['idUser'],$row['username'],$row['password'],$row['email'],$row['accessToken'],$row['refreshToken'],$row['admin']);
                $_SESSION['user']=$this->user;
                //echo $_SESSION['user'];
                $db->close();
                return $this->render("login.html.twig",["user" => $_SESSION['user']]);
            }
        }
        else{
            $db->close();
            return $this->render("login.html.twig",["error" => true]);
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){
        /*$session=$request->getSession();
        $session->clear();
        $session->set("status",0);*/
        $this->user=null;
        session_destroy();
        return $this->render("home.html.twig",["user" => null]);
        //return $this->redirectToRoute("home");
    }
}