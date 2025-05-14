<?php

require_once('../../../private/initialize.php'); //also need an id in url to this to check for admin status.

require_login();

$usID = $_GET['id'];

if(is_post_request()) {

  // Create record using post parameters
  $args = $_POST['Team']; //in document form you are creating a thing that has document so you get an associative array automatically with this code
  $Team = new Catagory($args);
  $result = $Team->save_prepared(); //creates or updates with this line of code here.


  if($result == true) {
    $new_id = $Team->ID;
    $Team->userscatAdd($Team->users, $new_id);

  //  $session->message('The bicycle was created successfully.');
    redirect_to(url_for('../../webpages/admin_webpages/home.php?id=' . h(u($usID))));
  } else {
    // show errors
  }

} else {
  // display the form
  $Team = new Catagory;
}

?>

<head>
  <title class=  "title">Visual Communication Tracker - Create New Team</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/teamWhite.png" height = 40px width = 40px />Create New Team</h1>
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



    <form action="<?php echo url_for("../../webpages/admin_webpages/newTeam.php?id=" . h(u($usID))); ?>" method="post">

      <?php include('../../webpages/admin_webpages/Category_Form_Fields.php'); ?>

      <div id="submit">
        <button onClick="refreshPage()" id="cancel">Cancel    </button>
        <input type="submit" value="Add New Team" id="sub">
      </div>
    </form>

  </div>

</div>

<script>
function refreshPage(){
  window.location.reload();
}
</script>
