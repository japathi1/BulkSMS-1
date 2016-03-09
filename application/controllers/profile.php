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

class Profile extends Controller {

    private $session;
    private $aView;
    private $lView;

    public function __construct(){
    	parent::__construct();
        $this->session = Registry::get("session");
        $this->aView = $this->getActionView();
        $this->lView = $this->getLayoutView();
        $this->lView->set("profile", true);
    }

    public function index(){
        if(RequestMethods::post("change")){
            $pass = RequestMethods::post("password");

            $user = User::first(array(
            "id = ?" => $this->session->get("user"),
            "live = ?" => 1
            ));

            if(!empty($user)){
                $user->password = $pass;
                $user->save();
                $this->aView->set("success", true);
            }
        }
    }
}
