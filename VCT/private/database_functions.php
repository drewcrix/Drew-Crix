<?php


function db_connect() { //user pdo connect
  try{
    $connection = new PDO(DB_SERVER, DB_USER, DB_PASS);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  }catch(PDOException $e){
    $error = $e->getMessage();
  }
  if(isset($error)){
    exit($error);
  }else{
    return $connection;
  }
}






 ?>
