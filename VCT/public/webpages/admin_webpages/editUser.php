<?php require_once('../../../private/initialize.php');


require_login();


$UserID = $_GET['id'];
$usID = $_GET['usID'];

$User = User::find_by_id_prepared($UserID);

if(is_post_request()){
  $args = $_POST['User'];
  $User->merge_attributes($args);
  $User->save_prepared();
}


?>


<head>
  <title class=  "title">Visual Communication Tracker - Edit User</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/personWhite.png" height = 40px width = 40px />Edit User</h1>
      </div>
      <div class="child-button">
        <div class="left">
          <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php?id=" . h(u($usID))) ; ?>"><img src="/shared/profileWhite.png" height = 40px width = 40px></a>
          <p class = "p">My Profile</p>
        </div>
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php?id=" . h(u($usID))) ; ?>"><img src="/shared/LOGOFFWhite.png" height = 40px width = 40px></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
    </div
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>

<div id="main">


  <div class="new">

    <form action="<?php echo url_for('../../webpages/admin_webpages/editUser.php?id=' . h(u($UserID)) . "&usID=" . h(u($usID))); ?>" method="post"> <?php //the action is where the form information gets sent through the post method"?>

      <?php

       include('../../webpages/admin_webpages/User_Form_Fields.php'); ?><br>

       <div id="submit">
         <input type="submit" value="Save" id="sub">
       </div>
    </form>
  <?php //  <button onClick="refreshPage()" id="cancel">Cancel</button> ?>
  </div>

</div>

<script>
function refreshPage(){
  location.reload();
}
</script>
