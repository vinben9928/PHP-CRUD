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
            $file = new File("users.json");
            $file->write($user);
        }
        catch(Exception $ex) {
            echo json_encode(["error" => $ex->getMessage()], JSON_FORCE_OBJECT);
        }
    }
    else {
        http_response_code(400);
        echo json_encode(["error" => "Bad request!"], JSON_FORCE_OBJECT);
    }
?>