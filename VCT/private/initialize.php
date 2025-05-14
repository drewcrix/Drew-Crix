<?php //this is an important file since it allows use to locate  this without locating through the server. By accessing through a public page.

  ob_start(); // turn on output buffering

  // Assign file paths to PHP constants
  // __FILE__ returns the current path to this file
  // dirname() returns the path to the parent directory
  define("PRIVATE_PATH", dirname(__FILE__));
  define("PROJECT_PATH", dirname(PRIVATE_PATH));
  define("PUBLIC_PATH", PROJECT_PATH . '/public'); //going into the public directory from the project path
  define("SHARED_PATH", PRIVATE_PATH . '/shared');

  error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

  // Assign the root URL to a PHP constant
  // * Do not need to include the domain
  // * Use same document root as webserver
  // * Can set a hardcoded value:
  // define("WWW_ROOT", '/~kevinskoglund/chain_gang/public');
  // define("WWW_ROOT", '');
  // * Can dynamically find everything in URL up to "/public"
  $public_end = strpos($_SERVER['SCRIPT_NAME'], '/public') + 7; //will this prevent user access into private directory???????
  $doc_root = substr($_SERVER['SCRIPT_NAME'], 0, $public_end);
  define("WWW_ROOT", $doc_root);


  require_once('functions.php');
  require_once('db_credentials.php');
  require_once('database_functions.php');


  // Load class definitions manually

  // -> Individually
  // require_once('classes/bicycle.class.php');

  // -> All classes in directory
  foreach(glob('C:\xampp\htdocs\VCT\private\classes\*.class.php') as $file) { //require each class in directory once. May have to change this since class is in a different directory
    require_once($file); //returns through all of them as an array and then loops through all of them
  }


  // Autoload class definitions

  function my_autoload($class) {
    if(preg_match('/\A\w+\Z/', $class)) {
      include('classes/' . $class . '.class.php');
    }
  }


  spl_autoload_register('my_autoload');

  $database = db_connect();
  dataBasegather::set_database($database);

  $session = new Session;
  if($session->is_logged_in() == false){
    $session->logout();
  }
/*  if($session->checkLog() == true){ //THIS CODE CAN BE USED TO AUTO LOGOUT BUT CHANGES TO THE PATH NEED TO BE DONE FOR IT TO WORK
    $parallel = $session->call_new_parallel();
    $rt = new \parallel\Runtime();
    //echo $parallel->login_time;
    $rt->run(function(){
      if(isset($parallel->login_time)){
        while(time() - $parallel->login_time < $parallel->logger){
          continue;
        }
        $id = $_GET['id'];
        $user = find_by_id_prepared($id);
        $session->logout($user);  //needs to have the session object to log the session out.
        redirect_to(url_for("../public/index.php"));
        $rt->kill();
      }

    });
    $rt->kill();
  }else{
    //do nothing
  } */


?>
