<?php

require_once("db/db.php");

class auth_model{
    private $db;
    private $user;

    public function __construct() {
        $this->db = Connect::connection();
        $this->user = [];
    }

    public function login($username, $password){
        
    }
}
?>