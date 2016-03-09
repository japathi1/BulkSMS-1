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

class Dashboard extends Controller {

	private $session;
    private $aView;
    private $lView;

    public function __construct(){
        parent::__construct();
        $this->session = Registry::get("session");
        $this->aView = $this->getActionView();
        $this->lView = $this->getLayoutView();
        $this->lView->set("home", true);
        $groups = Gname::all(array(
            "live = ?" => 1,
            "uid = ?" => $this->session->get("user")));
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
    public function index() {
    	if(RequestMethods::post("send")){
	        $view = $this->getActionView();
			$status = "Fill all form fields!";
			$status2 = '';
			$numbers = array();
			if(isset($_REQUEST['numbers']) && strlen($_REQUEST['numbers']) > 0){
				$numbers = explode(',', $_REQUEST['numbers']); //array((int)$_REQUEST['numbers']); //explode(',', $_REQUEST['numbers']);
			} else {
				$gid = RequestMethods::post("group");
				$n = Gmember::all(array(
					"uid = ?" => $this->session->get("user"),
					"live = ?" => 1,
					"gid = ?" => $gid
					));

				foreach ($n as $key => $value) {
					$v = Number::first(array(
						"uid = ?" => $this->session->get("user"),
						"live = ?" => 1,
						"id = ?" => $value->cid
						));
					$numbers[] = $v->number;
				}
			}

			//print_r($numbers);

			if(isset($_REQUEST['message']) && strlen($_REQUEST['message']) > 0 ){
				require APP_PATH . "/application/libraries/textlocal.class.php";
			    $message = $_REQUEST['message'];
			    $sender = "TXTLCL";
			    $conf = Registry::get("configuration");
			    $c = $conf->parse("configuration/textlocal");
			    if(!empty($c->textlocal) && !empty($c->textlocal->email) && !empty($c->textlocal->hash)){
				    $txt = new Textlocal($c->textlocal->email, $c->textlocal->hash);
				    $sched = (isset($_REQUEST['sched']) && strlen($_REQUEST['sched']) > 0)? (int) $_REQUEST['sched']:0;

				    if($sched != 0){
				        $time = (isset($_REQUEST['later']) && strlen($_REQUEST['later']) > 0)? (int) $_REQUEST['later']:0;
				        $status  = $txt->sendSms($numbers, $message, $sender, $time);//,true);
				        $status2 = $txt->err;
				    } else {
				        $status  = $txt->sendSms($numbers, $message, $sender);//,null,true);
				        $status2 = $txt->err;
				    }
				}
			}
			$view->set("status", print_r($status, true));
			$view->set("status2", $status2);
		}
    }
}
