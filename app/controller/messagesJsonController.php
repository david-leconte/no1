<?php

class MessagesJSONController {
    private $model;
    private $completeSearch;

    private $lastSeenTime;
    private $searchedTags;
    private $searchedUsers;

    public function __construct($search, $lastSeenTime) {
        require_once 'app/model/messagesJsonModel.php';

        $this->model = new MessagesJsonModel();
        $this->completeSearch = $search;
        
        if(intval($lastSeenTime) > 0) {
            $this->lastSeenTime = $lastSeenTime;

            $this->processSearch();
            $this->sendSearch();

            $this->render();
        }
    }

    private function processSearch() {
        preg_match_all("/(\#\w+)/i", $this->completeSearch, $foundTags);
        preg_match_all("/(\@\w{6})/i", $this->completeSearch, $foundUsers);

        $this->searchedTags = $foundTags[0];
        $this->searchedUsers = $foundUsers[0];
    }

    private function sendSearch() {
        $this->model->setLastSeen($this->lastSeenTime);

        if(!empty($searchedUsers)) {
            $this->model->setUserSearch($searchedUsers);
        }

        if(!empty($searchedTags)) {
            $this->model->setTagsSearch($searchedTags);
        }
    }

    private function render() {

    }
}

?>