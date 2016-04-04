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
        $result = ChamberModel::DeleteChamber_action(
              Session::get('user_name')
        );
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
	public function answer_action()
	{
		$answer = $_GET['value'];
		if (!Csrf::isTokenValid()) {
			LoginModel::logout();
            exit();
        } 
        chamberModel::Answer($answer);
       

	}
}












