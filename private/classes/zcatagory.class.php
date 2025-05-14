<?php

class Catagory extends dataBasegather{
  static protected $table_name = 'Category';
  static protected $db_columns = ['ID','Description','Dept'];

  public $ID;
  public $Description;
  public $Dept;
  public $users;

  public function __construct($args=[]){ //form would ask if you want to pick from one of the old departments or create a new department. Can be a yes no check and then if checked will open text submission for new and that would go under name or old submission for old. Then can do iffset name call the create new function and if not don't
   //description in the post will find the department Id and add that to the catagory table.
    $this->Dept = $args['Dept'] ?? '';
    if(isset($args['Description'])){
      $this->Description = $args['Description'].$this->getDeptName($this->Dept);
    }else{
      $this->Description = '';
    }

    $this->users = $args['Users'] ?? '';
  }


 public function updateDept($args = []){ //used if want to make a new department. //has to be called before instance of class. Do and iffset args[name]
   $dept = new Department($args);
   $dept->create_prepared();
 }

 public function userscatAdd($users, $catID){
   foreach($users as $use){
     $user = User::find_by_name($use);
     $parsed = parse($user->Cat);
     array_push($parsed,$catID);
     $user->Cat = implode(',',$parsed);
     $user->save_prepared();
   }
 }

private function getDeptName($deptID){
  $sql = "SELECT Name FROM Dept"." ";
  $sql .= "WHERE ID = :deptID";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':deptID',$deptID);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $answer = "(".$record['Name'].")";
  return $answer;
}



}

?>
