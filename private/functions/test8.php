<?php
//phpinfo();
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

$id = 1;

$user = User::find_by_id_prepared($id);
$user->DeptID = $user->departmentName($user->Cat[0]);

echo $user->UName;
echo $user->DeptID;

?>
