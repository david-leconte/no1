<?php

class mainModel {
	private $messages;
	private $usernameInfo;
	private $usernameColor;
	private $userIP;

	public function __construct($userIP, $directLoad = true) {
		$this->userIP = $userIP;

		if($directLoad) $this->getMessages();

		$this->usernameInfo = $this->getUsernameInfo();
	}

	// Get username information from username (created when and by who) or from the user's IP

	public function getUsernameInfo($username = null) {
		if($username == null) {
			$usernameInfoReq = App::$db->prepare('SELECT * FROM usernames WHERE assocIP = ? ORDER BY datetimeStart DESC');
			$usernameInfoReq->execute(array($this->userIP));
		}

		else {
			$usernameInfoReq = App::$db->prepare('SELECT * FROM usernames WHERE username = ? ORDER BY datetimeStart DESC');
			$usernameInfoReq->execute(array($username));
		}

		return $usernameInfoReq->fetch();
	}

	// Insert new username into the database
	
	public function insertNewUsername($username) {
		$insertUsernameReq = App::$db->prepare('INSERT INTO usernames (username, assocIP, datetimeStart) VALUES (?, ?, ?)');
		$insertUsernameReq->execute(array($username, $this->userIP, date('Y-m-d H:i:s')));
	}

	// Add a message
	
	public function addMessage($message) {
		$newMsgReq = App::$db->prepare('INSERT INTO messages (username, text, datetime) VALUES (?, ?, ?)');
		$newMsgReq->execute(array($this->usernameInfo['username'], $message, date('Y-m-d H:i:s')));
	}

	// Tries to delete a message, only works if the username associated to the IP is right

	public function tryDeletion($messageID, $username) {
		$delMsgReq = App::$db->prepare('DELETE FROM messages WHERE msgID = ? AND username = ?');
		$delMsgReq->execute(array($messageID, $username));
	}

	// MESSAGES RENDERED IN JS

	// Gets the first messages displayed to user without any search
	/*
	public function getMessages() {
		$getMsgReq = App::$db->prepare('SELECT * FROM messages ORDER BY datetime DESC LIMIT 10');
		$getMsgReq->execute();

		if(!$getMsgReq) die();

		$this->messages = [];

		foreach($getMsgReq->fetchAll() as $message) {
			$message['datetime'] = date("m/d/Y H:i:s", strtotime($message['datetime']));
			array_push($this->messages, $message);
		}

		return $this->messages;
	} */

}

?>