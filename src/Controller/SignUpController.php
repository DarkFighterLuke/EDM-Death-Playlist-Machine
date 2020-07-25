<?php
/**
 * Created by PhpStorm.
 * User: luca-
 * Date: 22/09/2019
 * Time: 19:15
 */

namespace App\Controller;


use App\Entity\LoginModel;
use App\Entity\SignUpModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use mysqli;


class SignUpController extends AbstractController
{

    /**
     * @Route("/signup", name="signup", methods="GET")
     */
    public function show()
    {
        return $this->render("signup.html.twig");
    }

    /**
     * @Route("/signup", methods="POST")
     */
    public function register()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        //$email=$_POST['email'];
        $countryCode = $_POST['countryCode'];
        $telephone = $_POST['telephone'];
        if (preg_match("/^.*([^A-Za-z0-9]).*$/", $username) == 1 || $username == null || $password == null || strlen($username) > 30 || strlen($password) > 16) {
            return $this->render("errorbase.html.twig", ["message" => "Username e/o password non validi."]);
        } else {
            $model = new SignUpModel();
            $result = $model->register($username, $password, $countryCode, $telephone);
            if ($result) {
                return $this->render("errorbase.html.twig", ["message" => "Username/Telefono già in uso."]);
            } else {
                return $this->render("successful.html.twig", ["operation" => "registrazione", "link" => "/"]);
            }
        }
    }
}