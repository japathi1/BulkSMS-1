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

class Group extends Controller {

    private $session;
    private $aView;
    private $lView;


    public function __construct(){
        parent::__construct();
        $this->session = Registry::get("session");
        $this->aView = $this->getActionView();
        $this->lView = $this->getLayoutView();
        $this->lView->set("group", true);

        $this->update();
    }

    public function update(){
        $groups = Gname::all(array(
            "live = ?" => 1,
            "uid = ?" => $this->session->get("user")));
        //print_r($this->);
        $this->aView->set("groups", $groups);
    }
/**
*@before _secure
*/
    public function index(){
        if(RequestMethods::post("add")){
            $this->add();
        } else if (RequestMethods::post("delete")){
            $this->delete();
        }
    }

    public function add(){
        
            $name = RequestMethods::post("name");
            $g = new Gname(array(
                "name" => $name,
                "uid" => $this->session->get("user"),
                "live" => 1
            ));

            if(!empty($g) && $g->validate()){
                $g->save();
                $this->aView->set("addSuccess", true);
                $this->aView->set("gName", $g->name);
            }        
        $this->update();     
    }

    public function delete($gid){
        $this->willRenderLayoutView = false;
        $this->willRenderActionView = false;
        $gid = (int) $gid;
        $g = Gname::first(array(
            "id = ?" => $gid,
            "live = ?" => 1
        ));
        if(!empty($g)){
            //print_r($g);
            $this->aView->set("gName", $g->name);
            //$g->live = 0;
            //$g->save();
            $g->delete();
            echo json_encode(array('success' => true));
            exit;
        }
            echo json_encode(array('success' => false));
            exit;
    }


}
