<?php

require_once('../../../private/initialize.php');

require_login(); //this page will also need to link to a user ID

$id = $_GET['userID'];

if(!isset($_GET['id'])) {
  redirect_to(url_for("../../webpages/admin_webpages/home.php?id=" . h(u($id))));
}
$iddoc = $_GET['id']; //in order for this edit document to work. Need to have a url for that includes the id in it

$document = Documents::find_by_id_prepared($iddoc); //finding the other attributes based on the id
if($document == false) {
  redirect_to(url_for("../../webpages/admin_webpages/home.php?id=" . h(u($id))));
}

if(is_post_request()) {
  $args = $_POST['document']; //may have too change this to document and the other one too document two to match form field
  $new = 1; //three means on hold
  $res = User_Doc::updatehold_prepared($iddoc,$new);
  $document2 = new Documents($args);
  $document2->Parent_ID = $document->Parent_ID;
  $document2->Version = $document->Version + 1;
  $result = $document2->save_prepared();
  if($result == true) {
    $new_id = $document2->ID;
    $use_doc = new User_Doc($id, $sort); //In this case a sort does not need to be done here a sort can be done on the admin home page where all documents with the userID of the admin will be querried and sorted
    $use_doc->adminAddDocs_prepared($document2, $id); //save_prepareds the document that was just added by the admin onto the user_doc class.
    redirect_to(url_for("../../webpages/admin_webpages/home.php?id=" . h(u($id))));
  } else {
      // show errors
  }





} else {

  // display the form

}


?>
<head>
  <title class=  "title">Visual Communication Tracker - Edit Existing</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/whiteAdddoc.png" height = 40px width = 40px />Edit Existing</h1>
      </div>
      <div class="child-button">
        <div class="left">
          <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php") ; ?>"><img src="/shared/profileWhite.png" height = 40px width = 40px></a>
          <p class = "p">My Profile</p>
        </div>
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php") ; ?>"><img src="/shared/LOGOFFWhite.png" height = 40px width = 40px></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
    </div
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>



<div id="main">

  <div class="new document">

    <form action="<?php echo url_for('../../webpages/admin_webpages/editResume.php?id=' . h(u($iddoc)) . '&userID=' . h(u($id))); ?>" method="post">

      <?php
       include('../../webpages/admin_webpages/document_form_fields.php'); ?><br>


      <div id="operations">
        <input type="submit" name="document[Publish]" value="Update" id="publish"/>
        <input type="submit" name="document[Draft]" value = "Save As Draft" id="draft"/>
      </div>
    </form>

  </div>

</div>
