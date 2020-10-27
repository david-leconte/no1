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

		// Routing between either a JSON page after a search or loading other messages, or the main page

		if((isset($_GET['json']) || isset($_POST['json'])) && isset($_POST['last-seen-msg'])) {
			require_once 'app/controller/messagesJsonController.php';

			if(isset($_POST['search'])) $currentController = new MessagesJsonController($_POST['last-seen-msg'], $_POST['search']);
			else $currentController = new MessagesJsonController($_POST['last-seen-msg']);
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