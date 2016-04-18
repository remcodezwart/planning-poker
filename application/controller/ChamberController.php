<?php
class ChamberController extends Controller
{
	public function __construct(){
        parent::__construct();  
        Auth::checkAuthentication();
    }
	public function index()
	{
		$this->View->render('chambers/chamber', array(
        	'chamber' => ChamberModel::GetRequestedChamber()
		));
	}
	public function createChamber()
	{
		$this->View->render('chambers/chamberCreate');

	}

	public function deletechamber()
	{
		$this->View->render('chambers/delete_chambers_confirm');


	}
	public function DeleteChamber_action()
	{
		if (!Csrf::isTokenValid()) {
            LoginModel::logout();
            Redirect::home();
            exit();
        }
        $result = ChamberModel::DeleteChamber_action();
	}
	public function CreateChamber_action()
	{
		if (!Csrf::isTokenValid()) {
            LoginModel::logout();
            Redirect::home();
            exit();
        }
		$result = ChamberModel::createchamber_action();
		if ($result) {
			Redirect::home();
		} else {
			redirect::to("chamber/createChamber");
		}
	}
	public function changeRoomName_action()
	{

		if (!Csrf::isTokenValid()) {
            LoginModel::logout();
            Redirect::home();
            exit();
        }
        $result = chamberModel::chageRoomname();
        if ($result) {
        	 Redirect::home();//if the user has tampered with the name for the field of id or if for some reason we do not get it were gonna redirect to home since we can not get them back cause we do not have the id
        } else {
        	Redirect::to('chamber/index/?id='.$_POST['id']);
        }
	}
	public function answer_action()
	{
		if (!Csrf::isTokenValid()) {
			LoginModel::logout();
              exit();
        } 
        $result = chamberModel::answer();
        if (!result) {
        	echo json_encode(array('error' => "error"));
        }
	}
	public function chekIfuseranswerAreFilled_action()
	{
		//if (!Csrf::isTokenValid()) {
		//	LoginModel::logout();
		//	Redirect::home();
        //    exit();
        //} 
        $resultAnswer = chamberModel::chekAnswers();
        if ($resultAnswer != null) {//prevents the result showing up on a null result
        	 echo json_encode($resultAnswer);
        }
	}
}