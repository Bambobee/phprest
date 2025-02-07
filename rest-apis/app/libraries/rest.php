<?php



require __DIR__ . '/../constants.php';
require __DIR__ . '/../models/DbConnect.php';
require __DIR__ . '/../models/customer.php';
require __DIR__ . '/../models/token.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Rest{

    protected $request;
    protected $serviceName;
    protected $param;
    protected $dbConn;
    protected $userId;

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is not valid.');
        }

        $handler = fopen('php://input', 'r');
        $this->request = stream_get_contents($handler);
        $this->validateRequest();

        $db = new DbConnect;
        $this->dbConn = $db->connect();

        if ('generatetoken' != strtolower($this->serviceName)) {
            $this->validateToken();
        }
    }

    public function validateRequest(){
        if($_SERVER['CONTENT_TYPE'] !== 'application/json'){
            $this->throwError(REQUEST_CONTENTTYPE_NOT_VALID, 'Request Content type is not Valid');
        }

        $data = json_decode($this->request, true);
        // print_r($data);

        if(!isset($data['name']) || $data['name'] == ""){
            $this->throwError(API_NAME_REQUIRED, 'API name is required.');
           
        }
        $this->serviceName = $data['name']; 

        if(!isset($data['param']) || !is_array($data['param'])){
            $this->throwError(API_PARAM_REQUIRED, 'API PARAM is required.');
            
        }
        $this->param = $data['param']; 
    }

    public function processApi(){
        $api = new API;
        $rMethod = new reflectionMethod('API', $this->serviceName);
        if(!method_exists($api, $this->serviceName)){
            $this->throwError(API_DOESNT_NOT_EXIST, 'API does not exist.');
        }
        $rMethod->invoke($api);
    }

    public function validateParameter($fieldName, $value, $dataType, $required = true){
        if($required == true && empty($value) == true){
            $this->throwError(VALIDATE_PARAMETER_REQUIRED, $fieldName . " parameter is required");
        }

        switch($dataType){
            case BOOLEAN : 
                if(!is_bool($value)){
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for this ' . $fieldName . 
                    " It should be a boolean");
                }
                break;
            case INTEGER : 
                if(!is_numeric($value)){
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for this ' . $fieldName . 
                    " It should be nnumeric.");
                }
                break;   
            case STRING : 
                if(!is_string($value)){
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for this ' . $fieldName . 
                    " It should be a string.");
                }
                break;  
            default:
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for this ' . $fieldName);
                break;
        }

        return $value;
    }


    public function throwError($code, $message){
        header("content-type: application/json");
        $errorMsg = json_encode(['error' => ['status'=>$code, 'message'=>$message]]);
        echo $errorMsg;
        exit;
    }
    public function returnResponse($code, $data){
        header("content-type: application/json");
        $response = json_encode(['response' => ['status' => $code, "result" => $data]]);
        echo $response;
        exit;
    }

    /*Get header Authorization*/
    public function getAuthorizationHeader(){
        $headers = null;

        if(isset($_SERVER['Authorization'])){
            $headers = trim($_SERVER["Authorization"]);
        }
        elseif (isset($_SERVER['HTTP_AUTHORIZATION'])){
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
        }elseif (function_exists('apache_request_headers')){
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if(isset($requestHeaders['Authorization'])){
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /* get access token from headers*/
    public function getBearerToken(){
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s+(.*)$/', $headers, $matches)) {
                return $matches[1];
            }
        }
        $this->throwError(ATHORIZATION_HEADER_NOT_FOUND, 'Access Token not found');
    }

    public function validateToken(){
        try{
            //    echo $token = $this->getBearerToken();
               $token = $this->getBearerToken();
               $payload = JWT::decode($token, new Key(SECRETE_KEY, 'HS256'));
              
               $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE id = :userId");
               // Bind the email parameter
               $stmt->bindParam(":userId", $payload->userId);
               // Execute the query
               $stmt->execute();
               // Fetch data in an associative array
               $user = $stmt->fetch(PDO::FETCH_ASSOC);
               // Check if the user exists
               if (!is_array($user)) {
                   $this->returnResponse(INVALID_USER_PASS, "This user is not found in your database.");
               }
                // Check if the user is active
            if ($user['active'] == 0) {
                $this->returnResponse(USER_NOT_ACTIVE, "This User may be deactivated. Please contact admin.");
            }
            //    print_r($payload->userId);
    
           $this->userId = $payload->userId;
           // Return a success response
        $this->returnResponse(SUCCESS_RESPONSE, "Token is valid and user is active.");
            }
            catch(Exception $e){
                $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
            }
        }
    }

   


?>