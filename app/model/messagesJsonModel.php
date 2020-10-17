<?php

class MessagesJsonModel {
    private $requestText;
    private $reqParameters;

    private $lastSeenDatetime;
    private $identifiedUsers;
    private $tags;

    public function __construct() {
        $this->requestText = "SELECT * FROM messages ";
    }

    public function setLastSeen($lastSeenTimestamp) {
        $this->lastSeenDatetime = date("Y-m-d H:i:s", $lastSeenTimestamp);
        $this->requestText = $this->requestText . ' WHERE datetime < :lastSeenDatetime';

        $this->reqParameters['lastSeenDatetime'] = $this->lastSeenDatetime;
    }

    public function setUserSearch($usernames) {
        $this->identifiedUsers = $usernames;
        $this->requestText = $this->requestText . ' AND (';

        for($i = 0; $i < count($usernames); $i++) {
            if($i > 0)  $this->requestText = $this->requestText . ' OR ';
            $this->reqParameters['username' . $i] ='%' . $usernames[$i] . '%';
            $this->requestText = $this->requestText . 'username LIKE :username' . $i;
        }

        $this->requestText = $this->requestText . ')';
    }

    public function setTagsSearch($tags) {
        $this->tags = $tags;

        for($i = 0; $i < count($tags); $i++) {
            $this->reqParameters['tag' . $i] = '%' . $tags[$i] . '%';
            $this->requestText = $this->requestText . ' AND text LIKE :tag' . $i;
        }
    }

    public function executeSearch() {
        $finalRequest = App::$db->prepare($this->requestText);
        $finalRequest->execute($this->reqParameters);

        return $finalRequest->fetchAll(PDO::FETCH_ASSOC);
    }
}