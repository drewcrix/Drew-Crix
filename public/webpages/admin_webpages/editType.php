<?php require_once('../../../private/initialize.php');

require_login();

$TypeID = $_GET['id'];

$Type = Type::find_by_id_prepared($TypeID);

if(is_post_request()){
  $args = $_POST['Type'];
  $Type->merge_attributes($args);
  $Type->save_prepared();
}


?>

<head>
  <title class=  "title">Visual Communication Tracker - Edit Type</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/typeNew.PNG" height = 40px width = 40px />Edit Type</h1>
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


  <div class="New">

    <form action="<?php echo url_for('../../webpages/admin_webpages/editType.php?id=' . h(u($TypeID))); ?>" method="post">

      <?php

       include('../../webpages/admin_webpages/Type_form_fields.php'); ?><br>

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
