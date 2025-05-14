<?php require_once('../../../private/initialize.php');


require_login();


$TeamID = $_GET['id'];
$usID = $_GET['usID'];

$Team = Catagory::find_by_id_prepared($TeamID);

if(is_post_request()){
  $args = $_POST['Team'];
  $Team->merge_attributes($args);
  $Team->save_prepared();
}


?>


<head>
  <title class=  "title">Visual Communication Tracker - Edit Team</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/teamWhite.png" height = 40px width = 40px />Edit Team</h1>
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

    <form action="<?php echo url_for('../../webpages/admin_webpages/editTeam.php?id=' . h(u($TeamID)) . "&usID=" . h(u($usID))); ?>" method="post"> <?php //the action is where the form information gets sent through the post method"?>

      <?php

       include('../../webpages/admin_webpages/Category_Form_Fields.php'); ?><br>

       <div id="submit">
         <button onClick="refreshPage()" id="cancel">Cancel    </button>
         <input type="submit" value="Save" id="sub">
       </div>
    </form>

  </div>

</div>

<script>
function refreshPage(){
  window.location.reload();
}
</script>
