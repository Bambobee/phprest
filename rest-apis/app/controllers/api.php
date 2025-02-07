<?php

// Enable CORS
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content response
    exit();
}

require __DIR__ . '/../libraries/Rest.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Api extends Rest {

    public function __construct() {
        parent::__construct(); 
        
        
    }


    public function generateToken() {
        $email = $this->validateParameter('email', $this->param['email'], STRING);
        $pass = $this->validateParameter('pass', $this->param['pass'], STRING);

        try{

        // Fetch the user's hashed password from the database
        $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email");

        // Bind the email parameter
        // $stmt->bindParam(":email", $email);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        // Execute the query
        $stmt->execute();

        // Fetch data in an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists
        if (!is_array($user)) {
            $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
        }

        // Verify the password
        if (!password_verify($pass, $user['password'])) {
            $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
        }

        // Check if the user is active
        if ($user['active'] == 0) {
            $this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact admin.");
        }

        // Prepare the payload for the JWT token
        $payload = [
            'iat' => time(),
            'iss' => 'localhost',
            'exp' => time() + (60 * 60), // Token expires in 1minute
            'userId' => $user['id']
        ];

        // Generate JWT Token
        $token = JWT::encode($payload, SECRETE_KEY, 'HS256');

        // Return the token in the response
        $data = ['token' => $token];
        $this->returnResponse(SUCCESS_RESPONSE, $data);
    }catch(Exception $e){
        $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
    }
    }

  
    // public function validateToken(){
    //     try{

    //         $headers = apache_request_headers();
    //         if(isset(headers["Authorization"])){

    //             $token = str_ireplace(search:"bearer ",  replace:"", $headers["Authorization"]);

    //             echo (new Functions)->validateToken($token);

    //         }

    //     }catch(Exception $e){
    //         $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
    //         exit;
    //     }
    // }

    // public function refreshToken(){

    // }

    // private function timeInFuture($expires): bool{
    //     return strtotime($expires) >= time();
    // }

    public function addCustomer(){
        //we have added false so that the code doesn't make values required it makes values optional
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $email = $this->validateParameter('email', $this->param['email'], STRING, false);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);
        $address = $this->validateParameter('address', $this->param['address'], STRING, false);
        

        $cust = new Customer;
        $cust->setName($name);
        $cust->setEmail($email);
        $cust->setAddress($address);
        $cust->setMobile($mobile);
        $cust->setCreatedBy($this->userId);
        $cust->setCreatedOn(date('Y-m-d'));

        if(!$cust->insert()){
            $message = 'Failed to insert. ';
        }else{
            $message = 'Inserted successfuly';
        }
        $this->returnResponse(SUCCESS_RESPONSE, $message);
      
    }

  

    public function getCustomerDetails(){
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

        $cust = new Customer;
        $cust->setId($customerId);
        $customer = $cust->getCustomerDetailsById();
        // print_r($customer);

        if(!is_array($customer)){
            $this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
        }

        $response['customerId'] = $customer['id'];
        $response['customerName'] = $customer['name'];
        $response['email'] = $customer['email'];
        $response['mobile'] = $customer['mobile'];
        $response['address'] = $customer['address'];
        $response['createdBy'] = $customer['created_user'];
        $response['lastUpdateBy'] = $customer['updated_user'];

        $this->returnResponse(SUCCESS_RESPONSE, $response);
    }

    public function updateCustomer(){
        //we have added false so that the code doesn't make values required it makes values optional
        
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);
        $address = $this->validateParameter('address', $this->param['address'], STRING, false);
        

        $cust = new Customer;
        $cust->setId($customerId);
        $cust->setName($name);
        $cust->setAddress($address);
        $cust->setMobile($mobile);
        $cust->setUpdatedBy($this->userId);
        $cust->setUpdatedOn(date('Y-m-d'));

        if(!$cust->update()){
            $message = 'Failed to update. ';
        }else{
            $message = 'Updated successfuly';
        }
        $this->returnResponse(SUCCESS_RESPONSE, $message);
      
    }

    public function deleteCustomer(){
        $customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

        $cust = new Customer;
        $cust->setId($customerId);

        if(!$cust->delete()){
            $message = 'Failed to Delete.';
        }
        else{
            $message = 'Deleted Successfully';
        }
        $this->returnResponse(SUCCESS_RESPONSE, $message);
    }
}
?>