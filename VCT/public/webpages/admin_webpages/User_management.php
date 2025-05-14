<?php require_once('../../../private/initialize.php');


require_login();

$usID = $_GET['id'];

if(isset($_GET['UserID'])){
  $UserID = $_GET['UserID'];
  $User = User::find_by_id_prepared($UserID);
  $User->delete();
}
?>

<head>
  <title class=  "title">Visual Communication Tracker - User Management</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/personWhite.png" height = 40px width = 40px />User Management</h1>
      </div>
      <div class="child-button">
        <div class="left">
          <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php?id=" . h(u($usID))) ; ?>"><img src="/shared/profileWhite.png" height = "40px" width = "40px"></a>
          <p class = "p">My Profile</p>
        </div>
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php?id=" . h(u($usID))) ; ?>"><img src="/shared/LOGOFFWhite.png" height = "40px" width = "40px"></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
  </div>
  <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>

<div class="main">
  <h1>List of Users</h1><br>
  <table id = "homeadmin">
    <tr>
      <th>Usernames</th>
      <th>Teams</th>
      <th colspan='2'>Update Status</th>
    </tr>
    <?php $result = User::find_all_prepared();
    foreach($result as $use){ ?>
      <tr>
        <td><?php echo $use->UName; ?></td>
        <td>
        <?php $catArray = $use->catName($use->Cat);
        foreach($catArray as $catDescript){
          echo $catDescript;
          echo ', ';
        } ?>
        </td>
        <td><a class = "readtheUP" href=<?php echo url_for("../../webpages/admin_webpages/editUser.php?id=" . h(u($use->ID)) . "&usID=" . h(u($usID))); ?>>Edit</a></td>
        <td><a class = "readtheUP" href=<?php echo url_for("../../webpages/admin_webpages/User_management.php?id=" . h(u($usID)) . '&UserID=' . h(u($use->ID))); ?>>Delete</a></td>
    <?php } ?>

  </div>
