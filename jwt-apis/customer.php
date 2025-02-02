<?php

class Customer{

    private $id;
    private $name;
    private $email;
    private $address;
    private $mobile;
    private $updatedBy;
    private $updatedOn;
    private $createdBy;
    private $createdOn;
    private $tableName = 'customers';
    private $dbConn;


    function setId($id){
        $this->id = $id;
    }
    function getId(){
        return $this->id;
    }
    function setName($name){
        $this->name = $name;
    }
    function getName(){
        return $this->name;
    }
    function setEmail($email){
        $this->email = $email;
    }
    function getEmail(){
        return $this->email;
    }
    function setAddress($address){
        $this->address = $address;
    }
    function getAddress(){
        return $this->address;
    }
    function setMobile($mobile){
        $this->mobile = $mobile;
    }
    function getMobile(){
        return $this->mobile;
    }
    function setUpdatedBy($updatedBy){
        $this->updatedBy = $updatedBy;
    }
    function getUpdatedBy(){
        return $this->updatedBy;
    }
    function setCreatedBy($createdBy){
        $this->createdBy = $createdBy;
    }
    function getCreatedBy(){
        return $this->createdBy;
    }
    function setCreatedOn($createdOn){
        $this->createdOn = $createdOn;
    }
    function getCreatedOn(){
        return $this->createdOn;
    }

    public function __construct(){
        $db = new Dbconnect();
        $this->dbConn = $db->connect();
    }

    public function getAllCustomers(){
        $stmt = $this->dbconn->prepare('SELECT * FROM ' . $this->tableName);
        $stmt->execute();
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $customers;
    }

    public function insert(){
        $sql = 'INSERT INTO ' . $this->tableName . '(id, name, email, address, mobile, created_by, created_on) VALUE (null, :name, :email,
         :address, :mobile, :createdBy, :createdOn)';

         $stmt = $this->dbConn->prepare($sql);
         $stmt->bindParam(':name', $this->name);
         $stmt->bindParam(':email', $this->email);
         $stmt->bindParam(':address', $this->address);
         $stmt->bindParam(':mobile', $this->mobile);
         $stmt->bindParam(':createdBy', $this->createdBy);
         $stmt->bindParam(':createdOn', $this->createdOn);

         if($stmt->execute()){
             return true;
         }else{
            return false;
         }
        //  $this->id = $this->dbConn->lastInsertId();
        //  return $this->id;
    }

    public function update(){

        $sql = 'UPDATE $this->tableName SET ';
        if(NULL != $this->getName()){
            $sql .= "name = '" . $this->getName() . "',";
        }
        if(NULL != $this->getEmail()){
            $sql .= "email = '" . $this->getEmail() . "',";
        }
        if(NULL != $this->getAddress()){
            $sql .= "address = '" . $this->getAddress() . "',";
        }
        if(NULL != $this->getMobile()){
            $sql .= "mobile = '" . $this->getMobile() . "',";
        }

        $sql .= "update_by = :updatedBy,update_on = :updatedOn WHERE id = :userId";
      
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':userId', $this->id);
        $stmt->bindParam(':updatedBy', $this->updatedBy);
        $stmt->bindParam(':updatedOn', $this->updatedOn);

        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    public function delete(){
        $stmt = $this->dbConn->prepare('DELETE FROM ' . $this->tableName . ' WHERE id = :userId');
        $stmt->bindParam(':userId', $this->id);

        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }
}
?>