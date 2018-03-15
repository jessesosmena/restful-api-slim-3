<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app = new \Slim\App;

$app->get('/rest/employees', function(Request $request, Response $response){
        
        $sql = "SELECT * FROM employees";

        try{
        	//get db object

        	$db = new myDB();
        	//connect

        	$db = $db->connect(); // connect to function / db.php

        	$stmt = $db->query($sql); // passing query to db
        	$employees = $stmt->fetchAll(PDO::FETCH_OBJ); // fetch query arrays & objects PDO method
        	$db = null;

        	echo json_encode($employees);

        }catch(PDOException $e){

        	echo '{"error": {"text": '.$e->getMessage().'}';

        }
});

// get single employee

$app->get('/rest/employee/{id}', function(Request $request, Response $response){

	    $id = $request->getAttribute('id'); // get attribute means the URI
     
        $sql = "SELECT * FROM employees WHERE id = '$id'";

        try{
        	//get db object

        	$db = new myDB();
        	//connect

        	$db = $db->connect(); // connect function / db.php

        	$stmt = $db->query($sql); // passing query to db
        	$employee = $stmt->fetchAll(PDO::FETCH_OBJ); // fetch arrays & objects
        	$db = null;

        	echo json_encode($employee);

        }catch(PDOException $e){

        	echo '{"error": {"text": '.$e->getMessage().'}';

        }
});

// add employee

$app->post('/rest/employee/insert', function(Request $request, Response $response){

	    $first_name = $request->getParam('first_name'); // set and getParam from form request
	    $last_name = $request->getParam('last_name'); 
	    $age = $request->getParam('age'); 
	    $position = $request->getParam('position'); 
	    $email = $request->getParam('email'); 
	  
        $sql = "INSERT INTO employees (first_name,last_name,age,position,email) VALUES (:first_name,:last_name,:age,:position,:email)";

        try{
        	//get db object

        	$db = new myDB(); // get class db
        	//connect

        	$db = $db->connect(); // connect function / db.php

        	$stmt = $db->prepare($sql);

        	$stmt->bindParam(':first_name', $first_name);  //bind sql query to param request
            $stmt->bindParam(':last_name', $last_name);
        	$stmt->bindParam(':age', $age);
        	$stmt->bindParam(':position', $position);
        	$stmt->bindParam(':email', $email);
           
            $stmt->execute(); 

            echo '{"notice": {"text": "Employee Added Successfully"}';


        }catch(PDOException $e){

        	echo '{"error": {"text": '.$e->getMessage().'}';

        }
});

// update customer

$app->put('/rest/employee/update/{id}', function(Request $request, Response $response){
        
        $id = $request->getAttribute('id'); // URI
        
        $first_name = $request->getParam('first_name'); // set and getParam from form request
	    $last_name = $request->getParam('last_name'); 
	    $age = $request->getParam('age'); 
	    $position = $request->getParam('position'); 
	    $email = $request->getParam('email'); 
	  
     
        $sql = "UPDATE employees SET
                       first_name = :first_name,
                       last_name = :last_name,
                       age = :age,
                       position = :position,
                       email = :email
                       WHERE id = '$id'";

        try{
        	//get db object

        	$db = new myDB(); // get class db
        	//connect

        	$db = $db->connect(); // connect function / db.php

        	$stmt = $db->prepare($sql);

            $stmt->bindParam(':first_name', $first_name);  //bind sql query to param request
            $stmt->bindParam(':last_name', $last_name);
        	$stmt->bindParam(':age', $age);
        	$stmt->bindParam(':position', $position);
        	$stmt->bindParam(':email', $email);

            $stmt->execute(); 

            echo '{"notice": {"text": "Employee Updated"}';


        }catch(PDOException $e){

        	echo '{"error": {"text": '.$e->getMessage().'}';

        }
});

// delete customer

$app->delete('/rest/employee/delete/{id}', function(Request $request, Response $response){

	    $id = $request->getAttribute('id'); // get attribute means get the URI
     
        $sql = "DELETE FROM employees WHERE id = '$id'";

        try{
        	//get db object

        	$db = new myDB();
        	//connect

        	$db = $db->connect(); // connect function / db.php

        	$stmt = $db->prepare($sql);
        	$stmt->execute();
        	$db = null;

            echo '{"notice": {"text": "Employee Deleted"}';

        }catch(PDOException $e){

        	echo '{"error": {"text": '.$e->getMessage().'}';

        }
});