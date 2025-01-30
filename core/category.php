<?php

class Category{

    //db stuff
    private $conn;
    private $table = 'categories';

    //post propersties
    public $id;
    public $name;
    public $created_at;

    //constructor with db connection
    public function __construct($db){
        $this->conn = $db;
    }

    //grtting posts from our database
    public function read(){

        //create query
        $query = 'SELECT * 
         FROM 
         ' . $this->table ;
         
         //prepare statement
         $stmt = $this->conn->prepare($query);
         //execute query
         $stmt->execute();

         return $stmt; 
    }

    public function create(){

        //create query
        $query = 'INSERT INTO ' . $this->table . ' SET name = :name';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //cleaning Param
        $this->name = htmlspecialchars(strip_tags($this->name));

        //binding paramenters
        $stmt->bindParam(':name', $this->name);

        //excute query
        if($stmt->execute()){
            return true;
        }else{
            //Error if something goes wrong
            printf("Error %s. \n", $stmt->error);
            return false;
        }
    }

}

?>