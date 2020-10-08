<?php 

class mainController {
	private $model;
	private $userIP;

	public function __construct() {
		require_once 'app/model/mainModel.php';

		$this->userIP = '128.128.0.1'; // $_SERVER['REMOTE_ADDR']
		$this->model = new mainModel(null, null, $this->userIP);
		$usernameInfo = $this->model->getUsernameInfo();

		if(!$usernameInfo || !$this->checkUsernameValidity($usernameInfo)) {
			$this->attributeUsername();
		}

		$this->checkForNewMsg();

		$this->render();
	}

	public function render() {
		if(isset($_GET['message']) && !empty($_GET['message'])) {
			$messages = $this->model->getMessages($load = true, $messageID = intval($_GET['message']));
		}

		else $messages = $this->model->getMessages($load = true, $messageID = null);
		
		require 'app/view/mainView.php';
	}

	public function checkForNewMsg() {
		if(isset($_POST['message'])) {
			if(!empty($_POST['message']) && strlen($_POST['message']) <= 140) {
				$this->model->addMessage($_POST['message']);
				header("Location: " .$_SERVER['PHP_SELF']);
			}
			else die();
		
		}
	}

	public function checkUsernameValidity($usernameInfo) {
		$currentTime = time();
		$usernameTimeStart = intval(strtotime($usernameInfo['datetimeStart']));

		if(($currentTime - $usernameTimeStart) > App::usernameDuration) return false;

		return true;
	}

	public function attributeUsername() {
		$newUsername = '';

		do {
			$usernameNotValid = false;

			$newUsername = substr(str_shuffle(App::usernameAllowedChars), 0, App::usernameLength);
			$newUsernameInfo = $this->model->getUsernameInfo($newUsername);

			if($newUsernameInfo) $usernameNotValid = $this->checkUsernameValidity($newUsernameInfo);
		} while($usernameNotValid == true);

		$this->model->insertNewUsername($newUsername);
	}

	public function colorFromUsername($username) {
		$firstCharCode = ord(substr($username, 0, 1));

		if($firstCharCode >= 48 && $firstCharCode <= 57) $color = 'blue';
		elseif($firstCharCode >= 65 && $firstCharCode <= 77) $color = 'green';
		elseif($firstCharCode >= 78 && $firstCharCode <= 90) $color = 'red';
		elseif($firstCharCode >= 97 && $firstCharCode <= 109) $color = 'purple';
		elseif($firstCharCode >= 110 && $firstCharCode <= 122) $color = 'orange';

		return $color;
	}
}

?>