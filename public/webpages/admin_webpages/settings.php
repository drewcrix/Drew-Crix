<?php require_once('../../../private/initialize.php');
require_login();
$id = $_GET['id']; //user ID

?>
<html lang="en">
  <head>
    <title class=  "title">Visual Communication Tracker - Admin Settings</title>
      <div class="grid-contain">
        <div class="child-title">
          <h1 class=  "title"><img src = "/shared/settingsWhite.png" height = 40px width = 40px />Admin Settings</h1>
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
  <body>
    <div class="backhome">
      <a id="home" href="<?php url_for("../../webpages/admin_webpages/home.php?id=" . h(u($id))) ; ?>"> << Back Home </a>
    </div>
    <div class="containor">
      <div id="flex-item">
        <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/Department_management.php?id=' . h(u($id))); ?>"><img src="/shared/departmentlogoBlue.png"  height = 100px width = 100px  alt="tracker page"></a>
        <p class="center">Manage Departments</p>
      </div>
      <div id="flex-item">
        <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/Team_management.php?id='. h(u($id)));?>"><img src="/shared/teamBlue.png"  height = 100px width = 100px alt="Global Tracker"></a>
        <p class="center">Manage Teams</p>
      </div>
    </div>
    <div class = "containor">
      <div id="flex-item">
        <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/Type_management.php?id='. h(u($id)));?>"><img src="/shared/TypeBlue.png"  height = 100px width = 100px alt="new doc"></a>
        <p class="center">Manage Types</p>
      </div>
      <div id="flex-item">
        <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/User_management.php?id='. h(u($id)));?>"><img src="/shared/person.png" height = 100px width = 100px alt="edit existing doc"></a>
        <p class="center">Update user premissions</p>
      </div>
    </div>
