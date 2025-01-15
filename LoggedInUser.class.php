<?php

class LoggedInUser{
    private $id;
    private $username;
    private $access_level;

    public function __construct($id, $username, $access_level){
        $this->id =$id;
        $this->username =$username;
        $this->access_level =$access_level;
    }


    public function getID(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }
    
    public function setUsername($username){
        $this->username = $username;
    }

    public function getAccessLevel(){
        return $this->access_level;
    }
    public function getAccessLevelString(){
        if($this->access_level === "0"){
            return "Administrator";
        }
        else if($this->access_level === "1"){
            return "Simple User";
        } 
    }

    public function setAccessLevel($access_level){
        $this->access_level = $access_level;
    }

}

?>