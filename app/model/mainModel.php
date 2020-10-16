<?php

class mainModel {
	private $messages;
	private $usernameInfo;
	private $usernameColor;
	private $userIP;

	public function __construct($userIP, $directLoad = true) {
		$this->userIP = $userIP;

		$this->getMessages($directLoad);

		$this->usernameInfo = $this->getUsernameInfo();
	}

	public function getMessages($load, $messageID = null) { // if load is set to false then we just take the messages already stored
		if($load) {
			if($messageID) {
				$getMsgReq = App::$db->prepare('SELECT * FROM messages WHERE id = ?');
				$getMsgReq->execute(array($messageID));
			}
			
			else {
				$getMsgReq = App::$db->prepare('SELECT * FROM messages LIMIT 10');
				$getMsgReq->execute();
			}

			if(!$getMsgReq) die();

			$this->messages = [];

			foreach($getMsgReq->fetchAll() as $message) {
				$message['datetime'] = date("m/d/Y H:i:s", strtotime($message['datetime']));
				array_push($this->messages, $message);
			}
		}

		return $this->messages;
	}

	public function addMessage($message) {
		$newMsgReq = App::$db->prepare('INSERT INTO messages (username, text, datetime) VALUES (?, ?, ?)');
		$newMsgReq->execute(array($this->usernameInfo['username'], $message, date('Y-m-d H:i:s')));
	}

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

	public function insertNewUsername($username) {
		$insertUsernameReq = App::$db->prepare('INSERT INTO usernames (username, assocIP, datetimeStart) VALUES (?, ?, ?)');
		$insertUsernameReq->execute(array($username, $this->userIP, date('Y-m-d H:i:s')));
	}
}

?>