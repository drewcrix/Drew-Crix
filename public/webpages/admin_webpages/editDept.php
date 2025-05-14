<?php require_once('../../../private/initialize.php');

require_login();

$DeptID = $_GET['id'];
$usID = $_GET['usID'];

$deptadd = Department::find_by_id_prepared($DeptID);

if(is_post_request()){
  $args = $_POST['Dept'];
  $deptadd->merge_attributes($args);
  $deptadd->save_prepared();
}


?>

<head>
  <title class=  "title">Visual Communication Tracker - Edit Dept</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/departmentLogowhite.png" height = 40px width = 40px />Edit Dept</h1>
      </div>
      <div class="child-button">
        <div class="left">
          <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php?id=" . h(u($usID))) ; ?>"><img src="C:\Users\acrix\Desktop\VCT\public\shared\profileWhite.png" height = 40px width = 40px></a>
          <p class = "p">My Profile</p>
        </div>
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php?id=" . h(u($usID))) ; ?>"><img src="C:\Users\acrix\Desktop\VCT\public\shared\LOGOFFWhite.png" height = 40px width = 40px></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
    </div
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>
<div id="main">


  <div class="new">

    <form action="<?php echo url_for('../../webpages/admin_webpages/editDept.php?id=' . h(u($DeptID))); ?>" method="POST"> <?php //the action is where the form information gets sent through the post method"?>

      <?php

       include('../../webpages/admin_webpages/Dept_Form_Fields.php'); ?><br>

       <div id="submit">
         <button onClick="refreshPage()" id="cancel">Cancel</button>
         <input type="submit" value="Save" id="sub">
       </div>
    </form>

  </div>

</div>
