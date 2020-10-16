<?php

class App {
	// App configuration

	// Database connection
	const siteName = "No1";
	const dbHost = "localhost";
	const dbName = "No1";
	const dbUser = "root";
	const dbPass = "";

	// Username parameters
	const usernameDuration = (60 * 60) * 24; // 24 hours
	const usernameAllowedChars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz'; // 
	const usernameLength = 6;

	public static $db;

	public static function route() {
		self::$db = new PDO("mysql:host=" .self::dbHost. ";dbname=". self::dbName, self::dbUser, self::dbPass);

		$currentController;

		if(isset($_GET['json']) || isset($_POST['json']) && !empty($_POST['search']) && !empty($_POST['last-seen-msg'])) {
			require_once 'app/controller/messagesJsonController.php';
			$currentController = new MessagesJsonController($_POST['search'], $_POST['last-seen-msg']);
		}

		else {
			require_once 'app/controller/mainController.php';
			$currentController = new MainController();
		}

		return $currentController;
	}
}

$controller = App::route();

?>