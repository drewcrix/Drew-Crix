<?php
require_once('../../private/initialize.php');

require_login();

if(isset($_GET['id'])){
  $id = $_GET['id'];

  $user = User::find_by_id_prepared($id);

  $user->DeptID = adminDepart_prepared($id, $database);

  $catdescript = $user->userCat($user->Cat);
}


if(is_post_request()){

  $args = $_POST['user'];
  $user->merge_attributes($args);
  $result = $user->save_prepared();


}


// a Tag redirects to the admin home page and if the user is not admin it will redirect to user home page.
?>
<head>
  <title class=  "title">Visual Communication Tracker - My Tracker</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src ="/shared/profileWhite.png" height = 40px width = 40px />My Profile</h1>
      </div>
      <div class="child-button">
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php") ; ?>"><img src="/shared/LOGOFFWhite.png" height = 40px width = 40px></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
  </div>
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>
<body>
  <div class="backhome">
    <a id="home" href="<?php url_for("../../webpages/admin_webpages/home.php?id=" . h(u($id))) ; ?>"> << Back Home </a>
  </div>
  <div class="user">

    <p> Welcome to your personal profile. Here you can update your preferred display name and teamas you're associated with. As your rotations change,always update your teams to ensure that your
      teams to ensure that you receive the appropriate updated information. If you require your department or program to be changed, contact a Hub Admin

    <form action="<?php url_for("../../webpages/My_Profile.php?id=" . h(u($id))) ; ?>" method='post'>
      <div class= grid-containors>
        <div class = grid-child>
          <label for="first">First Name:</label><br>
          <input type="text" name="user[FirstN]" id="first" value="<?php echo h($user->FirstN); ?>" disabled />
        </div>
        <div class = grid-child>
          <label for="last">Last Name:</label><br>
          <input type="text" name="user[LastN]" id="last" value="<?php echo h($user->LastN); ?>" disabled /><br>
        </div>
      </div>
     <div class = "grid-containors">
      <div class = "grid-child">
       <label for="email">Email Address:</label><br>
       <input type="text" name="user[email]" id="email" value="<?php echo h($user->email); ?>" disabled />
     </div>
     <div class= "grid-child">
       <label for="displayName">Display Name:</label><br>
       <input type="text" name = "user[DisplayName]" id="displayName" value="<?php echo h($user->DisplayName); ?>" /><br>
     </div>
   </div>


      <label>My Program(s): <?php echo $user->programName($user->Program) ; ?> </label><br><br>

      <label>My Department(s):
      <?php foreach($user->DeptID as $Dept){
        echo $Dept;
        echo ', ';
      } ?>
      </label><br><br>

      <label>My Team(s): </label>
        <select name="user[Cat][]" multiple>
          <?php
          $array = CatIDquarry_prepared($user->DeptID, $database); //write a function that gathers all the catagories that point to the one department.

          ?>
          <option value= "<?php echo $user->Cat ?>" selected>Select team(s)</option>
          <?php foreach($array as $key=>$value){ ?>
            <?php $xr = catIDtaker($value, $database); ?>
            <option value="<?php echo $xr; ?>"><?php echo $value; ?></option>
          <?php } ?>
        </select> <br><br>
        <div id="submit">
          <button onClick="refreshPage()" id="cancel">Cancel</button>
          <input type="submit" value="Save" id="sub">
        </div>
    </form>

    <script>
      function refreshPage(){
        window.location.reload();
      }
    </script>
