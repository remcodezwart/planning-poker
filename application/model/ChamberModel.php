<?php
class ChamberModel
{
	public static function getFeatureInfromation() {
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
		} else {
			Redirect::to('user/index');
			exit();
		} 
		$database = DatabaseFactory::getFactory()->getConnection();

		$chek = self::chekIfChamberExsist($database,$id);
		if ($chek === false) {
			Redirect::to('user/index');
			exit();
		}
		$query = $database->prepare("SELECT * FROM features where chamber_id=:chamberId AND active=:active"); 
		$query->execute(array(':chamberId' => $id,':active' => "1"));
		$answer = $query->fetchAll();

		$datbase = null;
	
		return $answer;
	}
	public static function GetRequestedChamber() {
		if (!isset($_GET['id'])) {
			Redirect::home();
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();
		$id = $_GET['id'];

		$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));

		$query = $database->prepare("SELECT features.active,users.user_id, users.user_name ,features.feature, features.id AS featureid,features.chamber_id, chambers.id, chambers.user_id, chambers.subject FROM chambers INNER JOIN features ON chambers.id=features.chamber_id INNER JOIN users ON chambers.user_id=users.user_id WHERE chambers.id=:id AND chambers.active=:active AND features.active=:active");
         $query->execute(array(':id' => $id,'active' => "1"));
         $chamber = $query->fetchAll();
         array_walk_recursive($chamber, 'Filter::XSSFilter');
         if ($chamber != null) {
         	Self::updateChamberUsers($id,$userId,$database);
         	return $chamber;
         } else {
         	Redirect::to('user/index');
         }
	}
	public static function DeleteChamber_action() {
		if (!isset($_GET['id'])) {
			Redirect::home();
			exit();
		}
		$id = $_GET['id'];
		$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));


		$database = DatabaseFactory::getFactory()->getConnection();
		$chambers = self::ChekOwner($database,$userId,$id);

		$chek = self::chekIfChamberExsist($database,$id);
		if ($chek === false) {
			Redirect::to('user/index');
			exit();
		}

		if ($chambers != null) {

			$query = $database->prepare("UPDATE answer SET active=:active WHERE chamber_id=:id"); 
			$query->execute(array(':active' =>"0" , 'id' => $id));

			$query = $database->prepare("UPDATE features SET active=:active WHERE chamber_id=:id"); 
			$query->execute(array(':active' =>"0" , 'id' => $id));

			$query = $database->prepare("UPDATE chamberusers SET active=:active WHERE chambers_id=:id"); 
			$query->execute(array(':active' =>"0" , 'id' => $id));

			$query = $database->prepare("UPDATE chambers SET active=:active WHERE user_id=:username AND id=:id"); 
			$query->execute(array(':active' =>"0" , ':username' => $userId , 'id' => $_GET['id']));

			redirect::home();
		} else {
			redirect::home();
		}

	}
	public static function createchamber_action() {
		$count = count($_POST);
		$count -=4;
		$chek = $count;

		if ($count < 0 || $count === null) {
			Session::add('feedback_negative', Text::get('EMPTY_STRING'));
			return false;
			exit();
		}
		
		if ($_POST['ChamberName'] == null || $_POST['Onderwerp'] == null) {
			Session::add('feedback_negative', Text::get('EMPTY_STRING'));
			return false;
			exit();
		}
		while($chek > 0) {
			if ($_POST[$chek] === null) {
				Session::add('feedback_negative', Text::get('EMPTY_STRING'));
				return false;
				exit();
			}
			$chek--;
		}
		$chek = $count;
		$database = DatabaseFactory::getFactory()->getConnection();

		$user_name = Session::get('user_name');
		$user_id = 	UserModel::getUserIdByUsername($user_name);


		$name = filter_var($_POST['ChamberName'], FILTER_SANITIZE_STRING);
		$subject = filter_var($_POST['Onderwerp'], FILTER_SANITIZE_STRING);

		if ($name === null || $subject === null) {
			Session::add('feedback_negative', Text::get('EMPTY_STRING'));
			return false;
			exit();
		}
		$features = array();
		while($chek > -1) {
			$cleaning = filter_var($_POST[$chek], FILTER_SANITIZE_STRING);
			if ($cleaning === null) {
				Session::add('feedback_negative', Text::get('EMPTY_STRING'));
				return false;
				exit();
			}
			array_push($features, $cleaning);
			$chek--;
		}
			$stmt = $database->prepare("INSERT INTO chambers (Name,subject,user_id,amout_of_features) VALUES(:name,:subject,:owner,:count)");
			$stmt->execute(array(':name' => $name , ':subject' => $subject, ':owner' => $user_id,':count' => $count+1));
					
			$query = $database->prepare("INSERT INTO features (feature,chamber_id) VALUES(:feature,:chamberid)");
			$last_id = $database->lastInsertId();
			$chek = $count;
		while($chek > -1) {
				$query->execute(array(':feature' => $features[$chek] , 'chamberid' => $last_id));
				$chek--;
			}
		$database = null;
		return true;	
	}
	public static function chageRoomname() {
		if (!isset($_POST['id'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
			}
		if (!isset($_POST['subject'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return true;
			exit();
			}
			$subject = strip_tags(trim($_POST['subject']));
		if ($_POST['subject'] == "") {
			Session::add('feedback_negative', Text::get('CHAMBER_NAME_EMPTY'));
			return true;
			exit();
		}
			$database = DatabaseFactory::getFactory()->getConnection();

			$user_name = Session::get('user_name');
			$user_id = 	UserModel::getUserIdByUsername($user_name);
			$id = $_POST['id'];

			$chambers = self::ChekOwner($database,$user_id,$id);
		if ($chambers != null) {
			$stmt = $database->prepare("UPDATE chambers SET Name=:subject WHERE user_id=:userid AND  id=:id");
			$stmt->execute(array(':userid' => $user_id,':id' => $_POST['id'],':subject' => $subject));
			Session::add('feedback_positive', Text::get('SUCCES_NAME_CHANGE'));
		} else {
			Session::add('feedback_negative', Text::get('NOT_OWNER'));
		}
			return true;
		}
	public static function answer() {
		if (!isset($_POST['value'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();

		$answer = filter_var($_POST['value'], FILTER_SANITIZE_STRING);
		if ($answer === null) {
			return false;
			exit();
		}
		$answer = explode('/', $answer);
		$answerValue = $answer[0];
		$id = $answer[1];
		$chamberId = $_POST['id'];

		Self::chekIfChamberExsist($database,$chamberId);

		$query = $database->prepare("SELECT * FROM features WHERE id=:id LIMIT 1");
		$query->execute(array(':id' => $id));
		$chek = $query->fetchAll();
		if ($chek === null) {
			return false;
			exit();
		}


		if ($answerValue == 1 || $answerValue == 2 || $answerValue == 3 || $answerValue == 4 || $answerValue == 5                                     || $answerValue == "joker") {

			$user_name = Session::get('user_name');
			$user_id = 	UserModel::getUserIdByUsername($user_name);

			$query = $database->prepare("SELECT * FROM answer WHERE users_id=:userid AND feature_id=:featureId");
			$query->execute(array(':userid' => $user_id,'featureId' => $id));
			$chambers = $query->fetchAll();
		
			if ($chambers == null) {
				$query = $database->prepare("INSERT INTO answer (users_id,feature_id,answer,chamber_id) VALUES(:user_id,:feature_id,:answer,:chamberId)");
				$query->execute(array(':user_id' => $user_id,'feature_id' => $id,':answer' => $answerValue,':chamberId' => $chamberId));
			} else {
				$query = $database->prepare("UPDATE answer SET answer=:answer WHERE feature_id=:id");
				$query->execute(array(':answer' => $answerValue,':id' => $id));
			}
			$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));
			Self::updateChamberUsers($chamberId,$userId,$database);
			$database = null;
			} else {
				return false;
				exit();
			}
	}
	protected static function updateChamberUsers($id,$userId,$database) {
		Self::chekIfChamberExsist($database,$id);

		$query = $database->prepare("SELECT * FROM chamberusers WHERE chambers_id=:id AND users_id=:userId"); 
		$query->execute(array(':id' => $id, ':userId' => $userId));
		$chambers = $query->fetchAll();

		$now = time() + 1200;
		if ($chambers != null) {
			$query = $database->prepare("UPDATE chamberusers SET expiry=:expiry WHERE users_id=:user");
			$query->execute(array(':expiry' => $now,
			':user' => $userId));
		} else {
			$query = $database->prepare("INSERT INTO chamberusers (users_id,chambers_id,expiry) VALUES (:id,:chamberId,:expiry);");
			$query->execute(array(':id' => $userId, ':chamberId' => $id, 'expiry' => $now));
		}
	}
	public static function chekAnswers() {
		if (!isset($_POST['id'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
		}
		$results = 0;
		$database = DatabaseFactory::getFactory()->getConnection();
		$id = $_POST['id'];

		Self::chekIfChamberExsist($database,$id);

		$now = time();
		$query = $database->prepare("SELECT * FROM chamberusers WHERE chambers_id=:id AND expiry>:now AND active=:active"); 
		$query->execute(array(':id' => $id,':now' => $now,':active' => "1"));
		$amoutOfusers = $query->fetchAll();

		$result = count($amoutOfusers);

		$answer = self::getFeatures($database,$id,$amoutOfusers);
		
		$results += count($answer);	
		
		$query = $database->prepare("SELECT chambers.amout_of_features,chambers.id FROM chambers WHERE chambers.id=:chamberId AND active=:active LIMIT 1"); 
		$query->execute(array(':chamberId' => $id, ':active' => "1"));
		$amoutOfFeatures = $query->fetchAll();

		foreach ($amoutOfFeatures as $temp) {
			$features = $temp->amout_of_features;
		}
		
		$features = (int)$features;

		$result = $result * $features;

		if ($result === $results) {
			
			$query = $database->prepare("UPDATE chambers SET eens=:eens"); 
			$query->execute(array(':eens' => "ja"));

			$query = $database->prepare("SELECT users.user_id,users.user_name,answer.users_id,answer.feature_id,answer.answer,chambers.id,features.feature,features.chamber_id,features.id FROM answer INNER JOIN users ON answer.users_id=users.user_id INNER JOIN features ON answer.feature_id = features.id INNER JOIN chambers ON features.chamber_id = chambers.id WHERE chambers.id=:id AND answer.active=:active"); 
			$query->execute(array(':id' => $id,':active' => "1"));
			$chamberResult = $query->fetchAll();
			array_walk_recursive($chamberResult, 'Filter::XSSFilter');
			
			return $chamberResult;

			$database = null;
		}
	}
	public static function chekIfChamberExsist($database,$id) {
		$query = $database->prepare("SELECT * FROM chambers WHERE id=:id AND active=:active"); 
		$query->execute(array(':id' => $id,':active' => "1"));
		$chek = $query->fetchAll();
		if ($chek == null) {
			return false;
			exit();
		}
	}
	protected static function ChekOwner($database,$userId,$id) {
		$query = $database->prepare("SELECT * FROM chambers WHERE user_id=:username AND id=:id limit 1"); 
		$query->execute(array(':username' => $userId , 'id' => $id));
		$chamber = $query->fetchAll();
		return $chamber;//makes it so if the user is not the only he or she can not edit/delete features and chambers
	}
	protected static function getFeatures($database,$id,$amoutOfusers){
		$query = $database->prepare("SELECT answer.id,answer.users_id,answer.feature_id,answer.answer,features.id,features.chamber_id FROM answer INNER JOIN features ON  answer.feature_id=features.id WHERE features.chamber_id=:chamberId AND answer.users_id=:id AND features.active=:active "); 

		foreach ($amoutOfusers as $amout){
		$query->execute(array(':id' => $amout->users_id,':chamberId' => $id , ':active' => "1"));
		}
		$answer = $query->fetchAll();
		array_walk_recursive($answer, 'Filter::XSSFilter');
		return $answer;
	}
	public static function deleteOneFeature()
	{
		if (!isset($_POST['id'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
		}
		if (!isset($_POST['value'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
		}
		$id = $_POST['id'];

		$answer = explode('/', $_POST['value']);

		$FeatureId = $answer[0];
		$chamberId = $answer[1];

		$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));
		$database = DatabaseFactory::getFactory()->getConnection();

		self::ChekOwner($database,$userId,$id);

		$query = $database->prepare("UPDATE features SET active=:remove WHERE id=:id AND chamber_id=:chamberId  limit 1"); 
		$query->execute(array(':remove' => "0" , ':id' => $FeatureId,':chamberId' => $chamberId));
		
		$query = $database->prepare("SELECT * FROM features WHERE chamber_id=:id AND active=:active "); 
		$query->execute(array(':active' => "1" , ':id' => $id));
		$answer = $query->fetchAll();

		$query = $database->prepare("UPDATE answer SET active=:active WHERE feature_id=:FeatureId"); 
		$query->execute(array(':active' => "0" , ':FeatureId' => $FeatureId));


		$caluclation = self::featureCalulation($database,$chamberId);
		$caluclation--;
       
		$query = $database->prepare("UPDATE chambers SET amout_of_features=:amout WHERE id=:id AND active=:active "); 
		$query->execute(array(':active' => "1" , ':id' => $chamberId, ':amout' => $caluclation));

		if ($answer == null) {
			$query = $database->prepare("UPDATE chambers SET active=:remove WHERE id=:id  limit 1"); 
			$query->execute(array(':remove' => "0" , ':id' => $chamberId));
		}
		$database = null;
		return true;	
	}
	public static function editOneFeature()
	{
		if (!isset($_POST['id'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
		}
		if (!isset($_POST['value'])) {
			Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
			return false;
			exit();
		}
		$id = $_POST['id'];

		$answer = explode('/', $_POST['value']);

		$FeatureId = $answer[0];
		$chamberId = $answer[1];
		$feature = filter_var($answer[2], FILTER_SANITIZE_STRING);
		if ($feature === null) {
			return false;
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();
		$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));
		self::ChekOwner($database,$userId,$id);

		$query = $database->prepare("UPDATE features SET feature=:feature WHERE chamber_id=:chamberId AND active=:active  AND id=:featureId" ); 
		$query->execute(array(':active' => "1" , ':chamberId' => $chamberId , ':feature' => $feature, ':featureId' =>    $FeatureId ));
		$database = null;
		return $feature;

		
	}
	public static function clean()
	{
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$id = array($id);
			array_walk_recursive($id, 'Filter::XSSFilter');
			return $id;
		}
	}
	public static function AddOneFeature()
	{
		if (!isset($_POST['id'])) {
			LoginModel::logout();
			Redirect::home();
            exit();
        } 
		
		$chamberId = $_POST['id'];
		$feature = $_POST['add'];

		$feature = filter_var($feature, FILTER_SANITIZE_STRING);
		if ($feature === null || $feature == "") {
			return false;
			exit();
		}
		$database = DatabaseFactory::getFactory()->getConnection();
		$userId = Usermodel::getUserIdByUsername(Session::get('user_name'));
		self::ChekOwner($database,$userId,$chamberId);
		$caluclation = self::featureCalulation($database,$chamberId);
		$caluclation++;

		$query = $database->prepare("UPDATE chambers SET amout_of_features=:amout WHERE id=:id AND active=:active "); 
		$query->execute(array(':active' => "1" , ':id' => $chamberId, ':amout' => $caluclation));

		$query = $database->prepare("INSERT INTO features (feature,chamber_id) VALUES (:feature,:chamberId)"); 
		$query->execute(array(':chamberId' => $chamberId, ':feature' => $feature));

		$last_id = $database->lastInsertId();


		return $returnValue = array("feature"=>$feature,"chamber"=>$chamberId,"id"=>$last_id);

	}
	protected static function featureCalulation($database,$chamberId)
	{
		$query = $database->prepare("SELECT chambers.amout_of_features FROM chambers WHERE id=:id AND active=:active LIMIT 1"); 
		$query->execute(array(':active' => "1" , ':id' => $chamberId));
		$amout = $query->fetchAll();
		foreach ($amout as $amout) {
			$caluclation = $amout->amout_of_features;
		}
		return $caluclation = (int)$caluclation;
	}
}