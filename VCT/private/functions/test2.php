<?php
//phpinfo();
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

$args = array();
$args['Name'] = "James";
$args['Hyperlink'] = 'www.legoland.com';
$args['Dept'] = 'brain research';
$args['Notify'] = 0;
$args['CatID'] = 'first floor'; //not giving the right catagory ID since it needs to match with the dept ID.
$args['notifyperiod'] = '8';
$args['day/monthnotify'] = 'weeks';
$args['NotifyDate'] = "october 13th";
$args['Expiry'] = 1;
$args['Type'] = 3;
$args['DueDate'] = "may 25th";
$args['ExpiryPeriod'] = 1;




$document = new Documents($args);
$result = $document->save_prepared(); //creates or updates with this line of code here.
$docID = $document->ID;



if($result == true) {
  $document->parentid_prepared($docID);
  $new_id = $docID;
//  $session->message('The bicycle was created successfully.');
  redirect_to(url_for('/staff/bicycles/show.php?id=' . $new_id));
} else {
  // show errors
}

//create date, Version, Parent_ID

//echo var_dump($args);
 ?>
