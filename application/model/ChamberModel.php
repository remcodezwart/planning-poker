<?php
class ChamberModel{
	public static function GetRequestedChamber()
	{
		if (!isset($_GET['id'])) {
			Redirect::home();
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();
		$id = $_GET['id'];

		$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));

		$query = $database->prepare("SELECT * FROM chamberusers WHERE chambers_id=:id AND users_id=:userId"); 
		$query->execute(array(':id' => $id, ':userId' => $userId));
		$chambers = $query->fetchAll();

		$now = time();
		if ($chambers != null) {
				$query = $database->prepare("UPDATE chamberusers SET expiry=:expiry WHERE users_id=:user");
				 $query->execute(array(':expiry' => $now,
				 	':user' => $userId));
		} else {
				$query = $database->prepare("INSERT INTO chamberusers (users_id,chambers_id,expiry) VALUES (:id,:chamberId,:expiry);");
				$query->execute(array(':id' => $userId, ':chamberId' => $id, 'expiry' => $now));
		}
	
		 $query = $database->prepare("SELECT features.feature, features.chamber_id, chambers.id, chambers.Name, chambers.subject, chambers.owner FROM chambers INNER JOIN features ON chambers.id=features.chamber_id WHERE chambers.id=:id");
         $query->execute(array(':id' => $id));
         $chamber = $query->fetchAll();
         return $chamber;
	}
	public static function DeleteChamber_action($username)
	{
		if (!isset($_GET['id'])) {
			Redirect::home();
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();
		$query = $database->prepare("SELECT * FROM chambers WHERE owner=:username AND id=:id limit 1"); 
		$query->execute(array(':username' => $username , 'id' => $_GET['id']));
		$chambers = $query->fetchAll();
		if ($chambers != null) {
			$query = $database->prepare("DELETE FROM chambers WHERE owner=:username AND id=:id"); 
			$query->execute(array(':username' => $username , 'id' => $_GET['id']));
			redirect::home();
		} else {
			redirect::home();
		}

	}
	public static function createchamber_action()
	{
		if ($_POST['ChamberName'] == null || $_POST['Onderwerp'] == null || $_POST['feature1'] == null || $_POST['feature2'] == null || 					$_POST['feature3'] == null || $_POST['feature4'] == null || $_POST['feature5'] == null || $_POST['feature6'] == null) {
		redirect::home();
		die();	
		} else {
			$database = DatabaseFactory::getFactory()->getConnection();
			$username = Session::get('user_name');

			$name = strip_tags(trim($_POST['ChamberName']));
			$subject = strip_tags(trim($_POST['Onderwerp']));
			$feature1 = strip_tags(trim($_POST['feature1']));
			$feature2 = strip_tags(trim($_POST['feature2']));
			$feature3 = strip_tags(trim($_POST['feature3']));
			$feature4 = strip_tags(trim($_POST['feature4']));
			$feature5 = strip_tags(trim($_POST['feature5']));
			$feature6 = strip_tags(trim($_POST['feature6']));
			$features = array($feature1,$feature2,$feature3,$feature4,$feature5,$feature6);

			$stmt = $database->prepare("INSERT INTO chambers (Name,subject,owner) VALUES(:name,:subject,:owner)");
			$stmt->execute(array(':name' => $name , ':subject' => $subject, ':owner' => $username));
		
			$query = $database->prepare("INSERT INTO features (feature,chamber_id) VALUES(:feature,:chamberid)");
			$last_id = $database->lastInsertId();
		for ($result = 0;$result <= 5; $result++) {
				if ($features[$result] != null) {
					if (!empty($features[$result])) {
						$query->execute(array(':feature' => $features[$result] , 'chamberid' => $last_id));
					}
				} 
			}
		redirect::home();
		}
	}
}
?>