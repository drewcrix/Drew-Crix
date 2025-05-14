
<?php require_once('../../../private/initialize.php');

require_login();

$hyperlink = $_GET['hyperlink'];
$id = $_GET['UsID'];
$docID = $_GET['DocID'];


?>
<html>

<head>
  <meta charset="utf-8">
  <title>Iframe page</title>
  <link rel="stylesheet" media="all" href="<?php echo url_for('../../stylesheets/headerstyle.css') ; ?>" />
</head>

<body>
  <div>
    <iframe src="<?php echo 'HTTP://'. $hyperlink ; ?>" height = "1000" width = "2200"></iframe>
  </div>
  <div id="main-2">
    <p>I acknowledge that I have fully read and comprehend the information contained on this page.</p>
    <div class="containor">
      <div id="flex-item-3">
        <img src="/shared/iframe.png" alt="brain" width = 80px height = 80px />
      </div>
      <div id="flex-item-2">
        <form action="<?php echo url_for('../../webpages/user_webpages/home.php?id=' . h(u($id)) . '&DocID=' . h(u($docID))); ?>" method = "POST">
          <img src="/shared/person.png" alt="person" width = 30px height = 30px />
          <input type="text" id="username" name="DOCusername" placeholder= "username"/>
          <img src="/shared/lock.png" alt="lock" width = 30px height = 30px/>
          <input type="password" id="password" name="DOCpassword" placeholder= "password" />
          <input class= "button" type="submit" name="Confirm" /><br><br><br>
        </form>
      </div>
    </div>
  </div>
</body>

</html>
