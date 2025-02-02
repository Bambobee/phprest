<?php
require __DIR__ . '/vendor/autoload.php'; // Include Composer's autoloader
use Firebase\JWT\JWT; // Import the JWT class
use Firebase\JWT\Key;

class Api extends Rest {

    public $dbConn;

    public function __construct() {
        parent::__construct();  
        $db = new Dbconnect();
        $this->dbConn = $db->connect(); 
    }

    public function generateToken() {
        $email = $this->validateParameter('email', $this->param['email'], STRING);
        $pass = $this->validateParameter('pass', $this->param['pass'], STRING);

        try{

        // Fetch the user's hashed password from the database
        $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email");

        // Bind the email parameter
        $stmt->bindParam(":email", $email);

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
            'exp' => time() + (60 * 60), // Token expires in 1hr
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

    public function addCustomer(){
        //we have added false so that the code doesn't make values required it makes values optional
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $email = $this->validateParameter('email', $this->param['email'], STRING, false);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], STRING, false);
        $address = $this->validateParameter('address', $this->param['address'], STRING, false);
        

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

        $cust = new Customer;
        $cust->setName($name);
        $cust->setEmail($email);
        $cust->setAddress($address);
        $cust->setMobile($mobile);
        $cust->setCreatedBy($payload->userId);
        $cust->setCreatedOn(date('Y-m-d'));

        if(!$cust->insert()){
            $message = 'Failed to insert. ';
        }else{
            $message = 'Inserted successfuly';
        }
        $this->returnResponse(SUCCESS_RESPONSE, $message);
        }
        catch(Exception $e){
            $this->throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
        }
    }
}
?>