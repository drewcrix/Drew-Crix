<?php
class Parallel {
  public $logger;
  public $login_time;

  public function __construct($recent_login,$status)
  {
    $this->$login_time = $recent_login;
    if($status != 1){
      $this->logger = 86400;
    }else{
      $this->logger = 900;
    }
  
  }

/*
  private function runner(){
    $thread = new \parallel\Runtime();
    $thread->run(function(){

      if(isset($login_time)){
        while(time() - $login_time < $logger){
          continue;
        }
        logout();  //needs to have the session object to log the session out.
        $thread->kill();
      }else{
        logout(); //session is not defined so this doesn't work.
        $thread->kill();
      }
    });
  }
  */
}
?>
