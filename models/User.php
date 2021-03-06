<?php
    class User
    {
        public $id;
        public $firstName;
        public $lastName;
        public $email;
        public $password;

        public function __construct($email, $password, $firstName, $lastName, $isNewUser = true) {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))      { throw new Exception("Invalid e-mail address!"); }
            if(!isset($firstName)   || strlen($firstName) <= 0) { throw new Exception("A first name must be specified!"); }
            if(!isset($lastName)    || strlen($lastName) <= 0)  { throw new Exception("A last name must be specified!"); }
            if(!isset($email)       || strlen($email) <= 0)     { throw new Exception("An email must be specified!"); }
            if(!isset($password)    || strlen($password) <= 0)  { throw new Exception("A password must be specified!"); }

            $this->id = round(microtime(true) * 1000.0);
            $this->email = $email;
            $this->password = (isset($isNewUser) && $isNewUser === true ? password_hash($password, PASSWORD_DEFAULT) : $password);
            $this->firstName = $firstName;
            $this->lastName = $lastName;
        }

        public function getFullName() {
            return $this->firstName . " " . $this->lastName;
        }
    }
?>