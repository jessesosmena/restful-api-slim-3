<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;
// get all data from auth table
$app->get('/auth', function(Request $request, Response $response){
        
        $sql = "SELECT * FROM auth";

        try{
        	//get db object

        	$db = new myDB();
        	//connect

        	$db = $db->connect(); // <= connect function/src/main/db.php

        	$sth = $db->query($sql); // passing query to db
        	$auth = $sth->fetchAll(PDO::FETCH_OBJ); // fetch query arrays & objects PDO method
        	$db = null;

        	echo json_encode($auth); 

        }catch(PDOException $e){

        	echo '{"error": {"text": '.$e->getMessage().'}'; // print error message
        }
});

// Register

$app->post('/signup', function(Request $request, Response $response) {

$name = $request->getParam('name');
$email = $request->getParam('email');
$password = $request->getParam('password');

$password = sha1($password);

$mysql = "select count(*) as count from auth WHERE email=:email"; // :email => request getParam email

$sql = "INSERT INTO auth (name,email,password) 
VALUES(:name,:email,:password)";

try{

$db = new myDB(); // import class myDB
$db = $db->connect(); // <= connect function/src/main/db.php

$sth = $db->prepare($mysql);
$sth->bindParam(':email', $email);
$sth->execute();
$row = $sth->fetch(); 
 
if($row['count']>0){
$output = array(
 'status'=>"0",
 'operation'=>"That email is already registered"
);
echo json_encode($output);
$db = null;
return;
}

else{
 
$sth = $db->prepare($sql);

$sth->bindParam(':name', $name);
$sth->bindParam(':email', $email);
$sth->bindParam(':password', $password);
$sth->execute();

$output = array(
 'status'=>"1",
 'operation'=>"success"
);

echo json_encode($output);
$db = null;
return;
}
 
}
catch(PDOException $e){

echo '{"error": {"text": '.$e->getMessage().'}';

}

});

//Login

$app->post('/login', function(Request $request, Response $response) {

$email= $request->getParam('email'); 
$password= $request->getParam('password');

$password = sha1($password);

$sql = "select count(*) as count from auth WHERE email=:email AND
password=:password";

try{

$db = new myDB(); // import class myDB
$db = $db->connect(); // <= connect function/src/main/db.php

$sth = $db->prepare($sql);

$sth->bindParam(':password', $password);
$sth->bindParam(':email', $email);

$sth->execute();

$row = $sth->fetch(); 

if($row['count']>0){ // if :email & :password input matches email/password 
$output = array(
'status'=>"1",
'login'=>"sucess",
);
}
else{
$output = array(
'status'=>"0",
'login'=>"fail",
);
}
}
 
catch(PDOException $e){
 
$output = array(
'status'=>"2",
'login'=>"error",
);
}
 
// $object = (object) $output;
 
echo json_encode($output);
$db = null;
});