<?php
require_once('../private/initialize.php');

$errors = [];
$username = '';
$password = '';

if(is_post_request()) { //if there is a post request will set the username and password

  $username = $_POST['username'] ?? '';
  $userldap = 'domain\\'.$username ;
  $password = $_POST['password'] ?? '';
  $ldap = ldap_connect("AD_Server_hostname.sw.ca");

  // Validations
  if(is_blank($username)) {
    $errors[] = "Username cannot be blank.";
  }
  if(is_blank($password)) {
    $errors[] = "Password cannot be blank.";
  }

  // if there were no errors, try to login
  if(empty($errors)) {
    $user = User::find_by_name($username);
    try{

    // test if admin found and password is correct
      if($user != false && $bind = ldap_bind($ldap, $userldap, $password)) { //if there is an admin and the password is verify then login the admin
      // Mark admin as logged in
      $session->login($user);
      $user->require_admin_status();
    } elseif($user == false && $bind = ldap_bind($ldap, $userldap, $password)) {
      $newUser = new User;
      $newUser->newUser($username);
      $session->login($newUser);
      $newUser->require_admin_status();

    }else{
      throw new Exception;
    }
  }catch (Exception $x){
    echo "log in unsuccessful";
  }
 }

}

?>

<title>Log in</title>
<link rel="stylesheet" href="<?php echo url_for("../stylesheets/loginstyle.css") ; ?>">
<div class = "grid-containor">

  <div class="grid-child-header">
    <img src="/shared/Logo Option 2 - White.png" alt="Company logo" width = 300px height = 100px><br>
    <p id="extraP">A powerfully simple system<br>
      for fast-paced environments</p>
  </div>

  <div id="content" class="grid-child-login">
    <h1 class="loginhead">Welcome Back</h1>
    <p class="logP">
     To access the <mark class="blue">Sunnybrook</mark> HotSpot dashboard,<br>
     please sign in with your network credentials
    </p>


    <form action="index.php" method="post">
      <img src="/shared/person.png" alt="person" width = 30px height = 30px>
      <input type="text" name="username" placeholder= "username" value="<?php echo h($username); ?>"/><br /><br>
      <img src="/shared/lock.png" alt="lock" width = 30px height = 30px>
      <input type="password" name="password" placeholder= "password" /><br /><br>
      <input class= "button" type="submit" name="Sign In" value="Sign In"  /><br><br><br>

    </form>

    <a href="<?php echo url_for("../login.html");?>">Forgot Password?</a>


  </div>


</div>
