<?php

include_once "connect.php";

if (!isset($_SESSION)) {
    session_start();
}

class Captcha
{
    public $captcha_zahlen =
    array(
        0 => array('w1', 'w3', 's1', 's2', 's3', 's4'),
        1 => array('s2', 's4'),
        2 => array('w1', 'w2', 'w3', 's2', 's3'),
        3 => array('w1', 'w2', 'w3', 's2', 's4'),
        4 => array('w2', 's1', 's2', 's4'),
        5 => array('w1', 'w2', 'w3', 's1', 's4'),
        6 => array('w1', 'w2', 'w3', 's1', 's3', 's4'),
        7 => array('w1', 's2', 's4'),
        8 => array('w1', 'w2', 'w3', 's1', 's2', 's3', 's4'),
        9 => array('w1', 'w2', 'w3', 's1', 's2', 's4')
    );
    public $captcha_number;
    public $captcha_md5;
    public $old_captcha_md5;
    public $use_session;
    private $captcha_secret = 'SECRET';
    private $session_name = 'captcha_session';

    public function __construct($varValue)
    {

        $this->use_session = is_array($_SESSION);
        $this->old_captcha_md5 = $this->getSessionCaptcha();
        $this->captcha_number = rand('111111', '999999');
        $this->captcha_md5 = $this->getmd5Captcha();
        $this->setSessionCaptcha($this->captcha_md5);
    }

    function getCaptchaArray()
    {

        return str_split($this->captcha_number, 1);
    }

    function getmd5Captcha($var = NULL)
    {

        if ($var == NULL) {
            $var = $this->captcha_number;
        }

        return md5($var . "-" . $this->captcha_secret);
    }

    function isActive($i, $id)
    {

        if (in_array($id, $this->captcha_zahlen[$i])) {

            return 'captcha_active';
        } else {

            return '';
        }
    }

    function isSpam($captcha, $old_captcha = NULL)
    {

        if ($this->use_session) {
            $old_captcha = $this->old_captcha_md5;
        }

        if ($old_captcha == NULL || $captcha == NULL || $captcha == '') {
            return true;
        }

        return ($this->getmd5Captcha($captcha) != $old_captcha);
    }

    function setSessionCaptcha($var)
    {

        if ($this->use_session) {
            $_SESSION[$this->session_name] = $var;
        }

        return NULL;
    }

    function getSessionCaptcha()
    {

        if ($this->use_session) {

            return $_SESSION[$this->session_name];
        } else {

            return NULL;
        }
    }
}

$main = new main;

class main
{

    // validate names for forms
    public static function validateName($str)
    {
        if (!preg_match('/[^a-zäüöß\s\-]/i', $str)) {
            return true;
        }

        return false;
    }

    // try any query execution and get error codes
    public static function tryExecute($stmt, $param, $conn, $commit = false)
    {

        // check if sent params are of array type, otherwise convert
        if (!is_array($param)) {
            $param = [$param];
        }

        try {

            // try execution of insertion
            if ($stmt->execute($param)) {

                if ($commit) {
                    $conn->commit();
                }

                return true;
            }
        } catch (PDOException $e) {

            // store thrown error values in array
            $error = [
                "message" => $e->getMessage(),
                "code" => $e->getCode()
            ];

            // rollback data
            $conn->rollback();

            // return that array
            return $error;
        }

        return false;
    }
}

class fileUpload
{

    public $allowedFileExtensions = ["jpg", "jpeg", "png", "pdf"];
    public $maxFileSize = 3145728;

    function flattenFileArray()
    {

        $files = $_FILES;
        $files2 = [];


        foreach ($files as $input => $infoArr) {

            $filesByInput = [];
            foreach ($infoArr as $key => $valueArr) {

                if (is_array($valueArr)) {

                    foreach ($valueArr as $i => $value) {

                        $filesByInput[$i][$key] = $value;
                    }
                } else {

                    $filesByInput[] = $infoArr;
                    break;
                }
            }

            $files2 = array_merge($files2, $filesByInput);
        }

        $files3 = [];

        foreach ($files2 as $file) {

            if (!$file['error']) $files3[] = $file;
        }

        return $files3;
    }

    function getFileExtension($file)
    {
        // convert array to object
        if (!is_object($file)) {
            $file = (object) $file;
        }

        // get file extension
        $fileBasename = basename($file->name);
        $fileExtension = strtolower(pathinfo($fileBasename, PATHINFO_EXTENSION));

        return $fileExtension;
    }

    function validateFileExtensions($file)
    {

        // get filextension
        $fileExtension = $this->getFileExtension($file);

        // check if file extension is in array
        if (in_array($fileExtension, $this->allowedFileExtensions)) {
            return true;
        }

        return false;
    }

    function validateFile($file)
    {

        $validateExtensions = $this->validateFileExtensions($file);

        if (!$validateExtensions) {

            $output = [
                "status" => false,
                "code" => 1,
                "message" => "file type not allowed"
            ];
            return (object) $output;
        } else if ($file->size > $this->maxFileSize) {

            $output = [
                "status" => false,
                "code" => 2,
                "message" => "file size exceeds 3 MB"
            ];

            return (object) $output;
        } else {

            $output = [
                "status" => true,
                "code" => 0,
                "message" => "successass"
            ];

            return (object) $output;
        }

        return false;
    }

    function uploadFile($file, $directory, $conn)
    {

        $targetDirection = $directory;

        // start validation process
        $validateFile = $this->validateFile($file);

        // handle response
        if (is_object($validateFile)) {
            switch ($validateFile->code) {
                case 0:

                    $temp = explode(".", $file->name);
                    $newFile = round(microtime(true)) . '.' . end($temp);

                    // rename file and add up to directory
                    $targetFile = $targetDirection . $newFile;

                    $moveFile = move_uploaded_file($file->tmp_name, $targetFile);

                    if ($moveFile) {

                        $validateFile->status = true;
                        $validateFile->newFile = $newFile;

                        return $validateFile;
                    } else {

                        $validateFile->status = false;
                        $validateFile->message = $file;
                        return var_dump($targetFile);
                    }
                    break;
                default:
                    return $validateFile;
                    break;
            }
        }

        return false;
    }
}
