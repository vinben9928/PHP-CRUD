<?php
    require_once(dirname(__DIR__) . "/models/File.php");
    require_once(dirname(__DIR__) . "/models/User.php");

    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if(isset($firstName) && isset($lastName) && isset($email) && isset($password)) {
        try {
            $user = new User($email, $password, $firstName, $lastName);
        }
        catch(Exception $ex) {
            
        }
    }
    else {
        
    }
?>