<?php

class Session { //create the session based on the admin id, password, and recent login.

  private $user_id;
  public $Username;
  private $recentlogin;
  static public $adminDoc = array();
  private $userstatus;

  public const login_time = 86400; //the amount of time in a day

//make sessions secure

  public function __construct(){
    session_start(); //starts the session or resumes the current one if already created.
    $this->check_stored_login();//assigns the session variables to the variables created
    if(isset($_SESSION['user_id'])){ //has not been logged out yet
      $this->recentlogin = time(); // reset the recentlogin to the current time
      //$thread = new Parallel($recentlogin,$userstatus);
      /*$task->shutdown(); //shutdown the old task
      $work->shutdown();//shutdowm the old work
      $work = new Work($work_id);
      $stat = $work->worker_id;
      $task = new Task($recentlogin,$stat); //create a new task with the new recentlogin
      $work->start();
      $task->start(); //start the task*/

    }

  }

  public function login($user){
    if($user){ //if user is inputed which will be an instance of the user class then it will login the user and save_prepared with sessions.
      session_regenerate_id();
      $this->user_id = $_SESSION['user_id'] = $user->ID;
      $this->Username = $_SESSION['Username'] = $user->UName;
      $this->userstatus = $_SESSION['userstatus'] = $user->Status;
      $this->recentlogin = $_SESSION['last_login'] = time();
    //  $thread = new Parallel($recentlogin,$userstatus);
      /*$work_id = $user->check_status();
      $work = new Work($work_id);
      $stat = $work->worker_id;
      $task = new Task($recentlogin); //
      $work->start();
      $task->start();*/


    }
    return true;
  }

  public function logout(){ //logouts the person by unsetting all the save_preparedd sessions.
    unset($_SESSION['user_id']);
    unset($_SESSION['Username']);
    unset($_SESSION['last_login']);
    unset($_SESSION['userstatus']);
    unset($this->user_id);
    unset($this->Username);
    unset($this->recentlogin);
    unset($this->userstatus);
  }

  private function check_stored_login(){
    if(isset($_SESSION['user_id'])){ //checking to see if the user id is set in anysession.
      $this->user_id = $_SESSION['user_id'];
      $this->user_id = $_SESSION['Username'];
      $this->recentlogin = $_SESSION['last_login'];
      $this->userstatus = $_SESSION['userstatus'];
    }
  }
  public function checkLog(){
    if(isset($_SESSION['user_id'])){
      return true;
    }else{
      return false;
    }
  }
  public function call_new_parallel(){
    return new Parallel($this->recentlogin,$this->userstatus);
  }
/*
  public function adminSession($ident){
    if(isset($_SESSION['adminDoc'])){
      array_push($_SESSION['adminDoc'], $ident);
      echo "hello";
      return $_SESSION['adminDoc'];
    }else{
      array_push(static::$adminDoc, $ident); //trying to use this code for the admin home but kinda sketchy and may require adding a new element to the database
      $_SESSION['adminDoc'] = static::$adminDoc;
      echo "tough luck bud";
      return $_SESSION['adminDoc'];
    }

  }
  */

  private function last_login_check(){ //call this function periodically in the main program until and eventually the logout will occur
    if(isset($this->recentlogin)){
      if(time() - $this->recentlogin < self::login_time){
        return true;
      }else{
        return false;  //if return false then logout the user.
      }
    }else{
      return false;
    }
}

public function is_logged_in() {
  return isset($this->user_id) && $this->last_login_check(); //maybe remove the last_login_check(); will check this later
}

}
