<?php
error_reporting(E_ALL ^ E_DEPRECATED);
require_once("REST.api.php");
require_once "libs/Database.class.php";
require_once "libs/Signup.class.php";

class API extends REST
{

    public $data = "";

    private $db = NULL;

    public function __construct()
    {
        parent::__construct(); // Init parent contructor
        // Initiate Database connection
        $this->db = Database::getConnection();
    }

    /*
         * Public method for access api.
         * This method dynmically call the method based on the query string
         *
         */
    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
        if ((int)method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 400);                // If the method not exist with in this class, response would be "Page not found".
    }

    /*************API SPACE START*******************/

    private function about()
    {

        if ($this->get_request_method() != "POST") {
            $error = array('status' => 'WRONG_CALL', "msg" => "The type of call cannot be accepted by our servers.");
            $error = $this->json($error);
            $this->response($error, 406);
        }
        $data = array('version' => $this->_request['version'], 'desc' => 'This API is created by Blovia Technologies Pvt. Ltd., for the public usage for accessing data about vehicles.');
        $data = $this->json($data);
        $this->response($data, 200);
    }

    private function verify()
    {
        if ($this->get_request_method() == "POST" and isset($this->_request['user']) and isset($this->_request['pass'])) {
            $user = $this->_request['user'];
            $password =  $this->_request['pass'];

            $flag = 0;
            if ($user == "admin") {
                if ($password == "adminpass123") {
                    $flag = 1;
                }
            }

            if ($flag == 1) {
                $data = [
                    "status" => "verified"
                ];
                $data = $this->json($data);
                $this->response($data, 200);
            } else {
                $data = [
                    "status" => "unauthorized"
                ];
                $data = $this->json($data);
                $this->response($data, 401);
            }
        } else {
            $data = [
                "status" => "bad_request"
            ];
            $data = $this->json($data);
            $this->response($data, 400);
        }
    }

    private function test()
    {
        $data = $this->json(getallheaders());
        $this->response($data, 200);
    }

    private function request_info()
    {
        $data = $this->json($_SERVER);
        $this->response($data, 200);
    }

    function generate_hash()
    {
        $bytes = random_bytes(16);
        return bin2hex($bytes);
    }

    function hash_verify()
    {
        if ($this->get_request_method() != "POST") {
            $error = array('status' => 'WRONG_CALL', "msg" => "The type of call can be accepted by our servers in POST.");
            $error = $this->json($error);
            $this->response($error, 406);
        }

        if (!isset($this->_request['password']) || !isset($this->_request['hash'])) {
            $error = array('status' => 'Not Acceptable', "msg" => "password is missing.");
            $error = $this->json($error);
            $this->response($error, 406);
        }

        $data['verified'] = password_verify($this->_request['password'], $this->_request['hash']);
        $data['hash_info'] = password_get_info($this->_request['hash']);

        $data = $this->json($data);
        $this->response($data, 200);
    }

    function user_signup()
    {
        if ($this->get_request_method() != "POST") {
            $error = array('status' => 'WRONG_CALL', "msg" => "The type of call can be accepted by our servers in POST.");
            $error = $this->json($error);
            $this->response($error, 406);
        }

        if (!isset($this->_request['username']) || !isset($this->_request['password'])) {
            $error = array('status' => 'Not Acceptable', "msg" => "username or password is missing.");
            $error = $this->json($error);
            $this->response($error, 406);
        }

        $username = (isset($this->_request['username'])) ? $this->_request['username'] : "default";
        $password = (isset($this->_request['password'])) ? $this->_request['password'] : "default";
        $email    = (isset($this->_request['email'])) ? $this->_request['email'] : "deafult";

        $signup = new Signup($username, $password, $email);
    }




    /*************API SPACE END*********************/

    /*
            Encode array into JSON
        */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_PRETTY_PRINT);
        } else {
            return "{}";
        }
    }
}

// Initiiate Library

$api = new API;
$api->processApi();
