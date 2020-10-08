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

	public static function init() {
		self::$db = new PDO("mysql:host=" .self::dbHost. ";dbname=". self::dbName, self::dbUser, self::dbPass);

		require_once 'app/controller/mainController.php';
		return new MainController();
	}
}

$main = App::init();

?>