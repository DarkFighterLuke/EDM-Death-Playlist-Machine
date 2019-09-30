<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 19:15
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use mysqli;


class SignUpController extends AbstractController
{

    /**
     * @Route("/signup", name="signup", methods="GET")
     */
    public function show(){
        return $this->render("signup.html.twig");
    }

    /**
     * @Route("/signup", methods="POST")
     */
    public function register(){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $email=$_POST['email'];
        $countryCode=$_POST['countryCode'];
        $telephone=$_POST['telephone'];
        if(preg_match("/^.*([^A-Za-z0-9]).*$/",$username)==1 || $username==null || $password==null || strlen($username)>30 || strlen($password)>16) {
            return $this->render("errorbase.html.twig", ["message" => "Username e/o password non validi."]);
        }
        else{
            $db=new mysqli("localhost","root","","my_edmdeathplaylistmachine");
            $statement=$db->prepare("SELECT username,email FROM user WHERE username=? OR email=?");
            $statement=$db->prepare("SELECT username,email FROM user WHERE username=? OR email=?");
            $statement->bind_param("ss",$username,$email);
            $statement->execute();
            $result=$statement->get_result();
            if($result->num_rows>0){
                $db->close();
                return $this->render("errorbase.html.twig", ["message" => "Username/Email già in uso."]);
            }
            else{
                $statement=$db->prepare("INSERT INTO user (username,password,email,countryCode,telephone,admin) VALUES(?,AES_ENCRYPT(?,'chiavetemporanea'),?,?,?,0)");
                $statement->bind_param("sssss",$username,$password,$email,$countryCode,$telephone);
                $statement->execute();
                $result=$statement->get_result();
                $db->close();
                return $this->render("successful.html.twig",["operation" => "registrazione", "link" => "/"]);
            }
        }
    }
}