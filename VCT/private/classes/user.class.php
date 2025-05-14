<?php

class User extends dataBasegather { //going to make a class admin that extends user
  static protected $table_name = 'Usertable';
  static protected $db_columns = ['ID', 'UName','Status','email','Cat','Program','DisplayName','FirstN','LastN'];


public $ID;
public $UName;
public $Status;
public $email;
public $Cat;
public $DeptID;
public $Program;
public $DisplayName;
public $FirstN;
public $LastN;

public function __construct($args=[]){ //would use this when creating form fields since the argument is the POST array information and it will set to the variables
  $this->UName = $args['UName'];
  $this->Status = $args['Status'];
  $this->email = $args['email'] ?? '';
  $this->DisplayName = $args['DisplayName'];
  if(isset($args['department'])){
    $this->DeptID = $this->updatedepart_prepared($args['department']) ?? '';
  }else{
    $this->DeptID = '';
  }
  if(isset($args['Catter'])){
    $this->Cat = $this->catID($this->DeptID,$args['Catter']) ?? '';
  }elseif(isset($args['Cat'])){
    $this->Cat = implode(",",$args['Cat']);
  }else{
    $this->Cat = '';
  }

  //$this->DeptID = $this->updatedepart_prepared($args['department']) ?? '';
  //$this->Cat = $this->catID($this->DeptID, $args['Cat']) ?? '';
  //run a join querry with the catagory table and the
}
//what does the user have to be able to do. The user can update things into the thing if admin and can be done in regular code calling save_prepared. which is a public function


//TRYING find_by_name with prepared statment

static public function find_by_name($name) { //used to find the user by user name and turn into object
  $sql = "SELECT * FROM " . static::$table_name . " ";
  $sql .= "WHERE UName = :name";
  $stmt = dataBasegather::$database->prepare($sql);
  $stmt->bindValue(':name',$name);
  $obj_array = static::find_by_stmt($stmt); //need to have escape string with $name
  //echo var_dump($obj_array);

  if(!empty($obj_array)) {
    return array_shift($obj_array);
  }else{
    return false;
  }
}


protected function catID($deptiden, $catparse){
  $catname = parse($catparse);
  $x = sizeof($catname);
  $i = 0;
  $y = 0;
  $sql = "SELECT ID FROM Category ";
  $sql .= "WHERE Dept= :DeptID";
  $sql .= "AND ";
  $sql .= "(";
  foreach($catname as $name){
    $i = $i+1;
    if($i == $x){
      $sql .= "WHERE Description= ? ";
    }else{
      $sql .= "WHERE Description= ? ";
      $sql .= "OR ";
    }

  }
  $sql .= ")";
  $stmt = dataBasegather::$database->prepare($sql);
  $stmt->bindValue(':DeptID',$deptiden, PDO::PARAM_INT);
  foreach($catname as $name){
    $y = $y+1;
    $stmt->bindValue($y, $name);
  }
  $stmt->execute();
  $imploder = array();
  while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($imploder, $record['ID']);
  }
  $FinalIDs = implode(",",$imploder);
  return $FinalIDs;

}

public function require_admin_status(){ //requrie user to have admin status. Admin status of three was for a global admin with all adminstrative access but that was never created
  if($this->Status == 1){
    redirect_to(url_for('../webpages/user_webpages/home.php?id=' . h(u($this->ID))));
  }elseif($this->Status == 2){
    redirect_to(url_for('../webpages/admin_webpages/home.php?id=' . h(u($this->ID))));
  }elseif($this->Status == 3){
    redirect_to(url_for('../globaladmin_webpages/home.php?id=' . h(u($this->Use_ID))));
  }
}

public function deptQuarry($deptID){ //input is going to be the parse array
  $sql = "SELECT Name FROM Dept ";
  $sql .= "WHERE ID = :num ";
  $stmt = dataBasegather::$database->prepare($sql);
  $stmt->bindValue(':num',$deptID);

}

public function newUser($username){ //in order to add the user to the database once they login for the first time
  $this->UName = $username;
  $this->Cat = "7";
  $this->email = $username."@sunnybrook.ca";
  $this->Program = 1;
  $this->save_prepared();
}


/*
static public function find_by_name($name) { //prepared statement Version
  $sql = "SELECT * FROM " . static::$table_name . " ";
  $sql .= "WHERE UName='" . '?' . "'";
  $prepare = (dataBasegather::$database)->prepare($sql);
  $prepare->bindValue(1, $name, PDO::PARAM_STR);
  $obj_array = static::find_by_sql($prepare);
  if(!empty($obj_array)) {
    return array_shift($obj_array);
  }else{
    return false;
  }
}
*/
/* this is the CHECK STATUS FUNCTION SO IF NEED USE IT!
static private function check_status($name) { //will use this to determine wether or not the user gets admin status. Used to authenticate user. //have to match the status to the username
  $sql = "SELECT * FROM " . static::$table_name . " ";
  $required_array = static::find_by_sql($sql); //gets the complete array
  while($answer = $required_array->fetch_assoc()){
    if($answer['name'] == $username){ //checking to find the array at which the name matches the username shown above
      $true_status === $answer['status']; //gets the status corresponding to the above username to grant admin permission.
      break;
    }else{
      continue;
    }
  }
  return $true_status;
}
*/
private function rename($first,$last){
  $arr = array($first, $last);
  $fullname = implode(" ", $arr);
  return $fullname;
}

protected function create() {
    $this->set_hashed_password();
    return parent::create(); //applies the set hashed password and updates the sql. Changes hashed password from the sql to what ever the changed hashed password is
}

protected function update() {
    $this->set_hashed_password();
    return parent::update();
}

// validation functions


protected function has_presence($value) {
    return !is_blank($value);
}

protected function has_length($value,$options){ //options is either min, max
    if($strlen($value) < $options('min') || $strlen($value) > $options('max')){
        return false;
    }else{
        return true; //if this function returns true then the password is off proper length
    }
}
protected function emailcheck($value) {
    $email_reg = '$this->name+@sunnybrook.ca'; //how to you make sure that you are calling name at the beginning. Also split name into first and last
    return preg_match($email_reg, $value) === 1; // will return true if the patterns match
}
protected function has_unique_username($name, $current_id = "0"){
    $user = $this->find_by_name($name);
    if($user === false || $user->id == $current_id){
        return true; //unique
    }else{
        return false; //not unique
    }
}

public function userCat($catID){
  $parsed = parse($catID);
  $x = sizeof($parsed);
  $sql = "SELECT Description FROM Category WHERE ";
  $i = 0;
  $sql .= "(";
  $result = array();
  foreach($parsed as $parse){
    $i = $i+1;
    if($i == $x){
      $sql .= "ID= ? ";
    }else{
      $sql .= "ID = ? ";
      $sql .= " OR ";
    }

  }
  $sql .= ")";
  $stmt = self::$database->prepare($sql);
  $y = 1;
  foreach($parsed as $parse){
    $stmt->bindValue($y, $parse);
    $y = $y+1;
  }
  $stmt->execute();
  while($record = $stmt->fetch(FETCH_ASSOC)){
    array_push($result, $record['Description']);
  }
  return $result;
}

public function userBelong($DeptName){
  $CatID = array();
  $sql = "SELECT Category.ID AS CatID FROM Category ";
  $sql .= "INNER JOIN Dept ON Dept.ID = Category.Dept ";
  $sql .= "WHERE Dept.Name = :DeptName";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':DeptName',$CatID);
  $stmt->execute();
  while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($CatID,$record['CatID']);
  }
  $sql = "SELECT * FROM Usertable WHERE ";
  return $deptName;
}


/*
protected function validate("Y/m/d") { //check to see if validate and if not return and error. if error is empty the password is validated.
$this->errors = []
if()
}
*/
//directs user to either admin webpage or user webpage


}


//whenever you echo something you can use the h key to echo it.
 ?>
