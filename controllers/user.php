<?php
    require_once(dirname(__DIR__) . "/lib/settings.php");
    require_once(dirname(__DIR__) . "/models/File.php");
    require_once(dirname(__DIR__) . "/models/User.php");

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json");

    $RequestMethod = $_SERVER["REQUEST_METHOD"];

    if(strcasecmp($RequestMethod, "POST") == 0) 
    {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        if(isset($firstName) && isset($lastName) && isset($email) && isset($password)) {
            try {
                $user = new User($email, $password, $firstName, $lastName);
                $file = new File("users.json");
                $file->write($user);
                header("Location: " . RELATIVE_DIR . "/?registered");
            }
            catch(Exception $ex) {
                echo json_encode(["error" => $ex->getMessage()], JSON_FORCE_OBJECT);
            }
        }
        else {
            http_response_code(400);
            echo json_encode(["error" => "Bad request!"], JSON_FORCE_OBJECT);
        }
    }
    else if(strcasecmp($RequestMethod, "GET") == 0)
    {
        $file = new File("users.json");
        echo $file->readRaw();
    }
?>