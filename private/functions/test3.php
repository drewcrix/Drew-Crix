<?php
 require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

 //include(SHARED_PATH . '/header_user.php');

 //require_login();
//should be able to use get now since the id is grabbed from the login directly. if this doesn't work try _SESSION['id']
 $id = 1;
 if(isset($_GET['sort'])){
   $sortBy = $_GET['sort'];
 }else{
   $sortBy = '';
 }
 $userdocs_id = new User_Doc($id,$sortBy); //if the save_prepared doesn't work try making an update and create function then do a find by id and if not found do create if found do update
 $require = $userdocs_id->object;
 foreach($require as $doc){
//  echo $doc->Name;
  echo ' ';
  echo (Catagory::find_by_id_prepared($doc->CatID))->Description;
  echo ' ';
 }
 //TESTING if updating the status of the document function work? If the button is clicked is the document status updated
 $doc = $require[0];
 $DOCID = $doc->ID;
 $userdocs_id->updatestatus_prepared($DOCID); //This works now need to implement this with clicking the signoff button.
 $status = $userdocs_id->return_status_prepared($DOCID);
 echo $status;
 ?>
