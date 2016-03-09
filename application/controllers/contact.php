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

class Contact extends Controller {

    private $session;
    private $aView;
    private $lView;


    public function __construct(){
        parent::__construct();
        $this->session = Registry::get("session");
        $this->aView = $this->getActionView();
        $this->lView = $this->getLayoutView();
        $this->lView->set("contact", true);

        $this->update();
    }

    public function update(){
        $groups = Gname::all(array(
            "live = ?" => 1,
            "uid = ?" => $this->session->get("user")
            ));

        $numbers = array();
        foreach ($groups as $key => $value) {
            $numbers[$value->id] = Gmember::count(array(
                "live = ?" => 1,
                "uid = ?" => $this->session->get("user"),
                "gid = ?" => $value->id
                ));
            //$numbers[$key] = count($n);
        }
        //print_r($numbers);
        $this->aView->set("groups", $groups);
        $this->aView->set("contacts", $numbers);
    }

/**
*@before _secure
*/
    public function index(){
        if(RequestMethods::post("add")){
            $this->add();
        } else if (RequestMethods::post("deleteall")){
            $this->deleteall();
        }
    }

    public function add(){
        $group_hash = RequestMethods::post("gid");
        $n = RequestMethods::post("numbers");

        if(isset($_FILES['userFile']['tmp_name'])){
            $file = $_FILES['userFile']['tmp_name'];
            //move_uploaded_file($file, '.\up.csv');
            $a = file_get_contents($file);
            $n = explode(',', $a);
            $successCount = 0;
        }else if(isset($n) && strlen($n>0)){
            $successCount = 0;
            $n = explode(',', $n);
        }

        foreach ($n as $key => $value) {
            if(strlen($value) == 12){
                $g = new Number(array(
                    "number" => $value,
                    "uid" => $this->session->get("user"),
                    "live" => 1
                ));
                if(!empty($g) && $g->validate()){
                    $g->save();
                    $successCount++;
                

                    $h = new Gmember(array(
                        "gid" => $group_hash,
                        "uid" => $this->session->get("user"),
                        "cid" => $g->id
                        ));
                    if(!empty($h) && $h->validate()){
                        $h->save();
                    }
                }
            }            
            //print_r($g->getErrors());
        }

        $this->update();        
        $this->aView->set("successCount", $successCount);
    }

    public function delete1($gid){
        echo $id;
    }
    
    public function delete($gid){       
        $this->willRenderLayoutView = false;
        $this->willRenderActionView = false; 
        $gid = (int) $gid;
        $successCount = 0;
        $g = Gmember::all(array(
            "gid = ?" => $gid,
            "live = ?" => 1,
            "uid = ?" => $this->session->get("user")
        ));

        foreach ($g as $key => $value) {

            $h = Number::all(array(
            "id = ?" => $g[$key]->cid,
            "uid = ?" => $this->session->get("user"),
            "live = ?" => 1
            ));

            if(!empty($h)) {
                foreach ($h as $k => $value) {
                    $h[$k]->delete();
                    $successCount++;
                }
                $g[$key]->delete();
            }
        }
        //$this->aView->set("dSuccessCount", $successCount);
        echo json_encode(array('success' => true, 'deleteCount' => $successCount));
        exit;
    }

}
