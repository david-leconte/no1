<?php

class MessagesJsonModel {
    private $requestText;
    private $reqParameters;

    private $lastSeenDatetime;
    private $identifiedUsers;
    private $tags;
    private $msgID;

    private $messages;

    public function __construct() {
        $this->requestText = "SELECT * FROM messages WHERE ";
    }

    public function setLastSeen($lastSeenTimestamp) {
        $this->lastSeenDatetime = date("Y-m-d H:i:s", $lastSeenTimestamp);
        $this->requestText .= 'datetime < :lastSeenDatetime';

        $this->reqParameters['lastSeenDatetime'] = $this->lastSeenDatetime;
    }

    public function setUserSearch($usernames) {
        $this->identifiedUsers = $usernames;
        $this->requestText .= ' AND (';

        for($i = 0; $i < count($usernames); $i++) {
            if($i > 0)  $this->requestText .= ' OR ';
            $this->reqParameters['username' . $i] ='%' . $usernames[$i] . '%';
            $this->requestText .= 'username LIKE :username' . $i;
        }

        $this->requestText .= ')';
    }

    public function setTagsSearch($tags) {
        $this->tags = $tags;

        for($i = 0; $i < count($tags); $i++) {
            $this->reqParameters['tag' . $i] = '%' . $tags[$i] . '%';
            $this->requestText .= ' AND text LIKE :tag' . $i;
        }
    }

    public function setIDSearch($msgID) {
        $this->msgID = $msgID;
        $this->reqParameters['msgID'] = $this->msgID;
        $this->requestText .= 'msgID = :msgID';
    }

    public function executeSearch() {
        $this->requestText .= " ORDER BY datetime DESC LIMIT 10";

        $finalRequest = App::$db->prepare($this->requestText);
        $finalRequest->execute($this->reqParameters);

        $this->messages = [];

        foreach($finalRequest->fetchAll(PDO::FETCH_ASSOC) as $message) {
            $message['datetime'] = date("m/d/Y H:i:s", strtotime($message['datetime']));
            array_push($this->messages, $message);
		}

		return $this->messages;
    }
}