<?php 


//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorizathion,XRequestrred-With');

//initializibg our api
include_once('../core/initialize.php');

//initantance category
$category = new Category($db);

//get row posted data
$data = json_decode(file_get_contents("php://input"));

$category->name = $data->name;

//create category

if($category->create()){
    echo json_encode(
        array('message' => 'category created.')
    );
}
else{
    echo json_encode(
        array('message' => 'category not created')
    );
}

 