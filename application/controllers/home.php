<?php

/**
 * The Default Example Controller Class
 *
 * @author Vijayant Saini
 */
use Shared\Controller as Controller;
use Shared\Model as Model;
use Framework\RequestMethods as RequestMethods;
use Framework\Registry as Registry;

class Home extends Controller {

    private $session;
    private $aView;
    private $lView;

    public function __construct(){
        parent::__construct();
        $this->session = Registry::get("session");
        $this->aView = $this->getActionView();
        $this->lView = $this->getLayoutView();
        $this->lView->set("home", true);
    }

    public function index() {
        $this->willRenderLayoutView = false;

        //$aView->set("user", $this->user);        
        //print_r($this->user);
    }

    public function login(){
        //$session = Registry::get("session");
        //$session->erase("user");
        //$view = $this->getActionView();

        $this->willRenderLayoutView = false;

        if(RequestMethods::post("login")){
            //$view->set("error", "Fill form fields!");
            $email = RequestMethods::post("email");
            $password = RequestMethods::post("password");
            $user = User::first(array("email = ?" => $email,
            "password = ?" => $password,
            "live = ?" => 1
            ));

            //print_r($user->name);
            if(!empty($user)){
                $this->setUser($user);
                header("Location: ./");
                exit();
            } else {
                $this->aView->set("error","Email/password are incorrect!");
            }
            
        }
    }

    public function logout(){
        $this->setUser(false);
        $this->session->erase("user");
        header("Location: ./");
        exit();
    }

    public function register(){
        $this->willRenderLayoutView = false;

    	$view = $this->getActionView();
    	//$db = Registry::get("database");
        $view->set("error", null);
    	if(RequestMethods::post("register")){
	    	$user = new User(array(
	    		"name" => RequestMethods::post("name"),
	    		"email" => RequestMethods::post("email"),
	    		"password" => RequestMethods::post("password"),
	    		"live" => 1,
	    		"admin" => 1
	    		));
            if($user->validate()){
               	$user->save();
                $view->set("success", true);
            }
            error_reporting(0);
            $view->set("error", $user->getErrors());
            //print_r($user->getErrors());
	    }
    }

    public function sync() {
    //$this->noview();
    $db = Registry::get("database");
    $db->sync(new User);
    $db->sync(new Gname);
    $db->sync(new Number);
    $db->sync(new Gmember);
    }



}
