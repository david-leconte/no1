<?php

class MessagesJsonModel {
    private $request;

    public function __construct() {
        $this->request = "SELECT * FROM messages ";
    }

    public function setLastSeen($lastSeenTime) {
        
    }

    public function setUserSearch($username) {
        
    }

    public function setTagsSearch($tags) {
        
    }
}