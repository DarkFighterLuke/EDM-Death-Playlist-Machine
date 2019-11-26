<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 16:08
 */

namespace App\Controller;


use App\Entity\LoginModel;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class LoginController extends AbstractController
{
    private $user;
    private $model;

    public function __construct(){
        session_start();
        $this->model=new LoginModel();
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
        $username=isset($_POST['username'])?$_POST['username']:null;
        $password=isset($_POST['password'])?$_POST['password']:null;

        $result=$this->model->checkLogin($username, $password);

        if($result){
            return $this->render("login.html.twig",["user" => $_SESSION['user']]);
        }
        else{
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
        $_SESSION["user"]=null;
        session_destroy();
        //return $this->render("home.html.twig",["user" => null]);
        return $this->redirectToRoute("home");
    }
}