<?php
//phpinfo();
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');




$iddoc = 6; //in order for this edit document to work. Need to have a url for that includes the id in it
$usID = 2;
$sort = "alphabetical";
$document = Documents::find_by_id_prepared($iddoc); //finding the other attributes based on the id
$use_doc = new User_Doc($usID, $sort);
$use_doc->adminAddDocs_prepared($document, $usID);


?>
