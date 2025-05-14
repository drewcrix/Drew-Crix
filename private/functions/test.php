<?php
//TEST 1 LOGIN
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');


$args = array();
$args['Name'] = "PIANOCONNECT Vol 2";
$args['Hyperlink'] = 'www.google.com';
$args['Dept'] = 'brain research';
$args['Notify'] = 2;
$args['CatID'] = 'first floor';
$args['notifyperiod'] = '6';
$args['day/monthnotify'] = 'weeks';
$args['NotifyDate'] = "october 3rd";
$args['day/monthduedate'] = 'weeks';
$args['Expiry'] = 1;
$args['Type'] = 3;
$args['DueDate'] = "april 12th";
$args['Publish'] = 1;
$args['sendeveryduedate'] = 7;




$iddoc = 4; //in order for this edit document to work. Need to have a url for that includes the id in it
$usID = 2;
$document = Documents::find_by_id_prepared($iddoc); //finding the other attributes based on the id
if($document == false) {
  redirect_to(url_for('/staff/bicycles/index.php'));
}
  $document2 = new Documents($args);
  $document2->Parent_ID = $document->Parent_ID;
  $document2->Version = $document->Version + 1;
  $result = $document2->save_prepared();
  if($result == true) {
    $new_id = $document2->ID;
    $use_doc = new User_Doc($usID, $sort); //In this case a sort does not need to be done here a sort can be done on the admin home page where all documents with the userID of the admin will be querried and sorted
    $use_doc->adminAddDocs_prepared_prepared($document2, $usID); //save_prepareds the document that was just added by the admin onto the user_doc class.
    $use_doc->updatestatus_prepared($new_id);
    echo $new_id;
    //redirect_to(url_for('/staff/bicycles/show.php?id=' . $new_id));


} else {

  // display the form

}

?>
