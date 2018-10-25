<?php
    require_once(dirname(__DIR__) . "/lib/settings.php");
    require_once(dirname(__DIR__) . "/models/File.php");
    require_once(dirname(__DIR__) . "/models/User.php");

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    $RequestMethod = $_SERVER["REQUEST_METHOD"];

    if(strcasecmp($RequestMethod, "POST") == 0) 
    {
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $editId = $_POST["editId"];
        $deleteId = $_POST["deleteId"];

        if(isset($firstName) && isset($lastName) && isset($email) && (isset($password) || isset($editId))) {
            try {
                if(!isset($editId)) {
                    //New user.
                    $user = new User($email, $password, $firstName, $lastName);
                    $file = new File("users.json");
                    $file->write($user);
                    header("Location: " . RELATIVE_DIR . "/?registered");
                }
                else {
                    //Edit user.
                    if(strlen($firstName) == 0 || strlen($lastName) == 0 || strlen($email) == 0) {
                        echo json_encode(["error" => "Please fill in all the required fields!"], JSON_FORCE_OBJECT);
                        return;
                    }
                    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo json_encode(["error" => "Invalid e-mail address!"], JSON_FORCE_OBJECT);
                        return;
                    }

                    $file = new File("users.json");
                    $user = $file->getObjectByProperty("id", $editId);

                    if($user != null) {
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->email = $email;

                        $file->setObjectByProperty("id", $editId, $user);

                        echo json_encode(["success" => true], JSON_FORCE_OBJECT);
                    }
                    else {
                        echo json_encode(["error" => "User not found!"], JSON_FORCE_OBJECT);
                    }
                }
            }
            catch(Exception $ex) {
                echo json_encode(["error" => $ex->getMessage()], JSON_FORCE_OBJECT);
            }
        }
        else if(isset($deleteId)) {
            $file = new File("users.json");

            if($file->deleteObjectByProperty("id", $deleteId)) {
                echo json_encode(["success" => true], JSON_FORCE_OBJECT);
            }
            else {
                echo json_encode(["error" => "User not found!"], JSON_FORCE_OBJECT);
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
    else {
        http_response_code(400);
        echo json_encode(["error" => "Bad request!"], JSON_FORCE_OBJECT);
    }
?>