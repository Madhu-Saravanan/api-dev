<?php

require_once "Database.class.php";

class Signup
{
    private $username;
    private $password;
    private $email;

    private $db;

    public function __construct($username, $password, $email)
    {
        $this->db = Database::getConnection();

        $this->username = $username;
        $this->email = $email;
        $this->password = $password;

        $this->userSignup();
    }

    private function hash_password()
    {
        return password_hash($this->password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    public function userSignup()
    {
        echo $this->password . "\n";
        $pass =  $this->hash_password();
        echo $pass;
    }
}
