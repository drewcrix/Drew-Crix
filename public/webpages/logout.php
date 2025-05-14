<?php
require_once('../../private/initialize.php');

$id = $_GET['id'];

$user = User::find_by_id_prepared($id);
if($user != false){
  $session->logout();
  redirect_to(url_for("../index.php"));
}

?>
