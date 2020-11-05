<?php

class MessagesJSONController {
    private $model;
    private $completeSearch;

    private $lastSeenTime;
    private $searchedTags;
    private $searchedUsers;
    private $searchedID;

    private $foundMessages;

    public function __construct($lastSeenTime, $searchByID, $search = null) {
        require_once 'app/model/messagesJsonModel.php';

        $this->model = new MessagesJsonModel();
        $this->completeSearch = $search;
        
        if(intval($lastSeenTime) > 0) {
            if($searchByID == true && $search) {
                $this->searchedID = intval($search);
            }

            else {
                $this->lastSeenTime = $lastSeenTime;
                if($searchByID == false && $search) {
                    $this->completeSearch = $search;
                    $this->searchedTags = $this->getTagsFromText($this->completeSearch);
                    $this->searchedUsers = $this->getUsersFromText($this->completeSearch);
                }
            }

            $this->sendSearch();

            $this->render();
        }
    }

    private function getTagsFromText($text) {
        $keywords = explode(" ", $text);
        $searchedTags = [];

        foreach($keywords as $keyword) {
            if($keyword[0] == "#") {
                $keyword = substr($keyword, 1);
                array_push($searchedTags, $keyword);
            }

            else if($keyword[0] != "@") array_push($searchedTags, $keyword);
        }

        return $searchedTags;
    }

    private function getUsersFromText($text) {
        $keywords = explode(" ", $text);
        $searchedUsers = [];

        foreach($keywords as $keyword) {
            if($keyword[0] == "@") {
                $identificationLength = App::usernameLength + 1;
                if(strlen($keyword) == $identificationLength) {
                    $keyword = substr($keyword, 1);
                    array_push($searchedUsers, $keyword);
                }
            }
        }

        return $searchedUsers;
    }

    private function sendSearch() {
        if(!empty($this->searchedID)) {
            $this->model->setIDSearch($this->searchedID);
        }

        else {
            $this->model->setLastSeen($this->lastSeenTime);
            
            if(!empty($this->searchedUsers)) {
                $this->model->setUserSearch($this->searchedUsers);
            }

            if(!empty($this->searchedTags)) {
                $this->model->setTagsSearch($this->searchedTags);
            }
        }

        $this->foundMessages = $this->model->executeSearch();
    }

    private function render() {
        require 'app/view/messagesJsonView.php';
    }
}

?>