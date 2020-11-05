<?php 

class mainController {
	private $model;
	private $userIP;

	// Direct load is for the main page without any search, if there is a search the search is processed by AJAX

	public function __construct($directLoad = true) {
		require_once 'app/model/mainModel.php';

		// Usernames are attributed by IP
		$this->userIP = 125.128.0.1; //$_SERVER['REMOTE_ADDR'];
		$this->model = new mainModel($this->userIP, $directLoad);
		$usernameInfo = $this->model->getUsernameInfo();

		if(!$usernameInfo || !$this->checkUsernameValidity($usernameInfo)) {
			$this->attributeUsername();
		}

		if(!$this->checkForDeletion() && !$this->checkForNewMsg()) $this->render();
	}

	// Checks wether a username is still valid depending on the validity limit set in the configuration

	private function checkUsernameValidity($usernameInfo) {
		$currentTime = time();
		$usernameTimeStart = intval(strtotime($usernameInfo['datetimeStart']));

		if(($currentTime - $usernameTimeStart) > App::usernameDuration) return false;

		return true;
	}

	// Attribute new username to a user, either new or whose username isn't valid anymore

	private function attributeUsername() {
		$newUsername = '';

		do {
			$usernameNotValid = false;

			// Shuffles the allowed characters to generate new username
			$newUsername = substr(str_shuffle(App::usernameAllowedChars), 0, App::usernameLength);
			$newUsernameInfo = $this->model->getUsernameInfo($newUsername);

			if($newUsernameInfo) $usernameNotValid = $this->checkUsernameValidity($newUsernameInfo);
		} while($usernameNotValid == true);

		$this->model->insertNewUsername($newUsername);
	}

	// Checks wheter a user tries to delete a message, and if he has the right to do so

	private function checkForDeletion() {
		if(!empty($_POST['delete']) && intval($_POST['delete']) > 0) {
			$this->model->tryDeletion(intval($_POST['delete']), $this->model->getUsernameInfo['username']);
		}
	}

	// Checks wether a new message has been sent and respects the characters limit
	
	private function checkForNewMsg() {
		$messageSent = false;

		if(!empty($_POST['new-message']) && strlen($_POST['new-message']) <= 140) {
			$this->model->addMessage($_POST['new-message']);
			
			$messageSent = true;
			//header("Location: " .$_SERVER['PHP_SELF']);
		}

		return $messageSent;
	}

	// Calls the view

	private function render() {
		/*if(!empty($_GET['message'])) {
			$messages = $this->model->getMessages($load = true, $messageID = intval($_GET['message']));
		}

		else $messages = $this->model->getMessages($load = true, $messageID = null); */
		
		require 'app/view/mainView.php';
	}
}

?>