<?php
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');
$id = 2;
$department = adminDepart_prepared($id,$database);

foreach($department as $dept){
  echo $dept;
  echo ' ';
}

$cat = CatIDquarry_prepared($department,$database);

foreach($cat as $x){
  echo $x;
  echo ' ';
}

//$document = Documents::find_by_id_prepared($id);
//echo $document->Name;
?>
