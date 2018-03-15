<?php

   class myDB{

   	   //properties

   	   private $dbhost = 'localhost'; 
   	   private $dbuser = 'root'; 
   	   private $dbpass = ''; 
   	   private $dbname = 'auth'; 

   	   //connect function

   	   public function connect(){
      	  $mysql_connect_string = "mysql:host=$this->dbhost;dbname=$this->dbname";

      	  $dbConn = new PDO($mysql_connect_string, $this->dbuser, $this->dbpass);

      	  $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      	  return $dbConn;
   	   }
   }