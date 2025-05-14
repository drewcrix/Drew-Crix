<?php
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');
$id = 2;
$admin = User::find_by_id_prepared($id);
$user_doc = new User_Doc($id,'');

echo $user_doc->UserID;


?>
