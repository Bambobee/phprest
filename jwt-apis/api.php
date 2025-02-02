<?php
require __DIR__ . '/vendor/autoload.php'; // Include Composer's autoloader
use Firebase\JWT\JWT; // Import the JWT class

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
            'exp' => time() + (60), // Token expires in 60 seconds
            'userId' => $user['id']
        ];

        // Generate JWT Token
        $token = JWT::encode($payload, SECRETE_KEY, 'HS256');

        // Return the token in the response
        $data = ['token' => $token];
        $this->returnResponse(SUCCESS_RESPONSE, $data);
    }
}
?>