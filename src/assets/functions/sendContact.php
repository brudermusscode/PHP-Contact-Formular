<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/mysql/functions.php";

header('Content-type: application/json');
$captcha = new Captcha($_SESSION);

//print_r($_POST);
//print_r($_FILES["fileUpload"]);
//var_dump($sess);

// output for js script
$errorInformation = [
    "status" => false,
    "message" => "fill 0ut all forms",
    "collection" => []
];

if (
    !empty($_REQUEST["firstname"]) &&
    !empty($_REQUEST["lastname"]) &&
    !empty($_REQUEST["mail"]) &&
    !empty($_REQUEST["captcha"]) &&
    !empty($_REQUEST["old_captcha"])
) {

    $r = [
        "firstname" => $_REQUEST["firstname"],
        "lastname" => $_REQUEST["lastname"],
        "mail" => $_REQUEST["mail"],
        "captcha" => $_REQUEST["captcha"],
        "oldCaptcha" => $_REQUEST["old_captcha"]
    ];

    // objectify request array
    $r = (object) $r;

    // commit at first query
    $commit = true;

    // assume no files where submitted
    $hasFiles = false;

    // check if a file was chosen
    if ($_FILES["fileUpload"]["size"] > 0) {

        $fileUpload = new fileUpload;

        // objectify FILES array
        $files = (object) $_FILES["fileUpload"];

        // user has submitted files
        $hasFiles = true;

        // do not commit with upcoming query
        $commit = false;
        $allowFileUpload = false;
        $targetDirectory = $_SERVER["DOCUMENT_ROOT"] . "/files/";
    }

    // start transaction
    $pdo->beginTransaction();

    // validate firstname
    if ($main->validateName($r->firstname)) {

        // validate lastname
        if ($main->validateName($r->lastname)) {

            // validate email
            if (filter_var($r->mail, FILTER_VALIDATE_EMAIL)) {

                // check captcha input for spam protection
                if (!$captcha->isSpam($r->captcha, $r->oldCaptcha)) {

                    // all fine, insert into database
                    $insertContact = $pdo->prepare("INSERT INTO contacts (firstname, lastname, mail) VALUES (?,?,?)");
                    $insertContact = $main->tryExecute($insertContact, [$r->firstname, $r->lastname, $r->mail], $pdo, $commit);

                    if (!is_array($insertContact) && $insertContact) {

                        // get last inserted id to set relation between contacts and attachements
                        $lastContactId = $pdo->lastInsertId();

                        // go in here if files have been chosen
                        if ($hasFiles) {

                            // commit on next query
                            $commit = true;

                            // upload attachment
                            $uploadFile = $fileUpload->uploadFile($files, $targetDirectory, $pdo);

                            // check upload status
                            if ($uploadFile->status) {

                                // save new created, unique file name
                                $newFile = $uploadFile->newFile;

                                // insert file into database
                                $insertContactAttachment = $pdo->prepare("INSERT INTO contacts_attachments (cid, fileName) VALUES (?,?)");
                                $insertContactAttachment = $main->tryExecute($insertContactAttachment, [$lastContactId, $newFile], $pdo, $commit);

                                // check if insertion was successful...
                                if (!is_array($insertContactAttachment) && $insertContactAttachment) {

                                    $errorInformation["status"] = true;
                                    $errorInformation["message"] = "Your contact has been added + attachment";
                                    exit(json_encode($errorInformation));

                                    // ...otherwise give error output and rollback
                                } else {

                                    $errorInformation["message"] = "Something went wrong, try again!";
                                    exit(json_encode($errorInformation));

                                    $pdo->rollback();
                                }
                            } else {
                                $errorInformation["message"] = $uploadFile->message;
                                exit(json_encode($errorInformation));
                            }

                            // commit the following if no files have been chosen
                        } else {

                            $errorInformation["status"] = true;
                            $errorInformation["message"] = "Your contact has been added";
                            exit(json_encode($errorInformation));
                        }
                    } else {

                        switch ($insertContact["code"]) {
                            case "23000":
                                $errorInformation["message"] = "This mail address exists already";
                                exit(json_encode($errorInformation));
                                break;
                            default:
                                $errorInformation["message"] = $insertContact["message"];
                                exit(json_encode($errorInformation));
                                break;
                        }

                        $pdo->rollback();
                    }
                } else {
                    $errorInformation["message"] = "You've entered a bad captcha code";
                    exit(json_encode($errorInformation));
                }
            } else {
                $errorInformation["message"] = "Please enter a real E-Mail address";
                exit(json_encode($errorInformation));
            }
        } else {
            $errorInformation["message"] = "Your lastname has invalid characters";
            exit(json_encode($errorInformation));
        }
    } else {
        $errorInformation["message"] = "Your firstname has invalid characters";
        exit(json_encode($errorInformation));
    }
} else {
    $errorInformation["message"] = "Fill out all forms";
    exit(json_encode($errorInformation));
}
