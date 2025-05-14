<?php

class dataBasegather {

  static protected $database;
  static protected $table_name = ""; //one value that is shared amoung all instances of a class.
  static protected $table_components = [];

  static public function set_database($data) { //sets the inputed database from initialize equal to the database variable given here
    self::$database = $data;
  }

 static protected function instantiate($record){ //turns into object
   $object = new static; //creating a new static but not passing any argument
  // Could manually assign values to properties
  // but automatically assignment is easier and re-usable
  foreach($record as $property => $value) { //property is going to key to a value since associative array
    if(property_exists($object, $property)) {
      $object->$property = $value; //need to have the doller sign in front of property for it to run through all the properties.

    }
  }
  return $object;
}





//Prepared Version
static public function find_by_stmt($stmt){ //sql gathered from subclasses of this function. //istantiates
  $result = $stmt->execute();


//  foreach(self::$database->query($sql) as $row){
  //  echo var_dump($row);
//  }
  if(!$result){
    exit("Database query failed.");
  }

$object_array = [];
while($record = $stmt->fetch(PDO::FETCH_ASSOC)) {
  //var_dump($record);
  $object_array[] = static::instantiate($record);
}

$stmt->closeCursor();

return $object_array;  //now have array of instances
}



static public function find_by_id_prepared($id){ //finds an entry by ID
  $sql = "SELECT * FROM " . static::$table_name . " "; //self means bound to class that it is defined. like typing dataBasegather::$table_name;
  $sql .= "WHERE id = :ID";//single quotes to enclose id and double quotes for dynamic
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':ID',$id);
  $foundid = static::find_by_stmt($stmt);
  if(!empty($foundid)){
    return array_shift($foundid);
  }else{
    return false;
  }
}


static public function find_all(){
  $sql = "SELECT * FROM" . static::$table_name;
  return static::find_by_sql($sql);
}

static public function find_all_prepared(){ //find's all data entry from a database table and returns as objects
  $sql = "SELECT * FROM " . static::$table_name;
  $stmt = self::$database->prepare($sql);
  return static::find_by_stmt($stmt);
}



protected function create_prepared(){ //create new entry for database
  $attributes = $this->sanitized_attributes();
  $sql = "INSERT INTO " . static::$table_name . " (";
  $sql .= join(', ', $table_name[0]);
  $sql .= join(', ', array_keys($attributes));
  $sql .= ") VALUES ('";
  $sql .= join("', '", array_values($attributes));
  $sql .= "')";
  $stmt = self::$database->prepare($sql);
  $result = $stmt->execute();
  if($result) {
    $this->ID = $attributes['ID'];//insert_id is a key variable to a database.
  }
  return $result;
}

// Properties which have database columns, excluding ID
public function attributes() { //sets the attributes as objects. Values taken from the database columns from the class that calls this function
  $attributes = [];
  foreach(static::$db_columns as $column) { //static will be for the class it is running in.
    //echo $this->$Name;
    if($column == 'ID') {
      if(isset($this->ID)){
        $attributes[$column] = $this->ID;
        continue;
      }else{
        $attributes[$column] = $this->IncrementID_prepared();
        continue;
      }
    }
    $attributes[$column] = $this->$column;
  }
  //var_dump($attributes);
  return $attributes;
}

protected function sanitized_attributes() {
  $sanitized = [];
  foreach($this->attributes() as $key => $value) {
  //  $sanitized[$key] = self::$database->escape_string($value); //need some sort of escape string for this to sanitize properly
      $sanitized[$key] = $value;
  }
  //var_dump($sanitized);
  return $sanitized;
  }


public function save_prepared() {
  if(isset($this->ID)){ //call all the id's under one singular id for this to work.Or have this as a primary key
    return $this->update_prepare();
  }else{
    return $this->create_prepared(); //give all the databases id
  }
}


protected function update_prepare(){ //updates a data entry
  $attributes = $this->sanitized_attributes();
  $attribute_pairs = [];
  foreach($attributes as $key => $value) {
    $attribute_pairs[] = "{$key}='{$value}'";
    }

  $sql = "UPDATE " . static::$table_name . " SET ";
  $sql .= join(', ', $attribute_pairs);
//  $sql .= " WHERE id='" . self::$database->escape_string($this->id) . "' ";
  $sql .= " WHERE id = :ID";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':ID',$this->ID);
  $result = $stmt->execute();
  return $result;

}

public function merge_attributes($args){ //when updating doc is merges the attributes that were added to the post with the object attribues so it can be updated. After you merge the new object is defined with the new updates. Then you call $object->save_prepared() for the entry to be updated to the appropriate database
  foreach($args as $key => $value){
    if(property_exists($this,$key) && !is_null($value)){
      $this->$key = $value;
      if($key == 'Cat'){
        $this->$key = implode(",",$value);
      }
    }
  }
}

public function delete() {
  $sql = "DELETE FROM " . static::$table_name . " ";
  $sql .= "WHERE id = :ID";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':ID',$this->ID);
  $result = $stmt->execute();
  return $result;


}





private function IncrementID_prepared(){ //used this so the ID is incremented when a new table entry is added
  $sql = "SELECT TOP 1 * FROM " . static::$table_name . " ";
  $sql .= "ORDER BY ID DESC ";
  $stmt = self::$database->prepare($sql);
  $result = $stmt->execute();
  if(!$stmt){
    return 1;
  }else{
    $answer = $stmt->fetch(PDO::FETCH_ASSOC);
    return $answer["ID"]+1;
  }
}

protected function parsedata($CatID){ //takes the string data and parse into an array of integers which will represent the catagories that the document will be assigned too
  $CatID_array = explode(",",$CatID);
  return $CatID_array;
}


protected function updatedepart_prepared($Dept){ //returns depart id
  $sql = "SELECT * FROM Dept"." ";
  $sql .= "WHERE Name = :Dept";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':Dept', $Dept);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $answer = (int)$record['ID'];
  return $answer;
}

protected function updatetype_prepared($Type){
  $sql = "SELECT * FROM Type"." ";
  $sql .= "WHERE Description = :Dept";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':Dept', $Type);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $answer = (int)$record['ID'];
  return $answer;
}

protected function updateprogram_prepared($program){
  $sql = "SELECT * FROM Program"." ";
  $sql .= "WHERE Name = :Dept";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':Dept', $program);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $answer = (int)$record['ID'];
  return $answer;
}


protected function updatecat_prepared($CatID,$Dept){
  $sql = "SELECT * FROM Category"." ";
  $sql .= "WHERE Description = :CatID AND Dept = :Dept";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':CatID',$CatID);
  $stmt->bindValue(':Dept',$Dept);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $answer = (int)$record['ID'];
  return $answer;
}


public function adminHome_prepared($id){ //used for getting the documents for the admin view page. These are just docs posted by admin
  $Objarray = array();
  $x = 1;
  $sql = "SELECT DocID FROM User_Docs WHERE UserID = :ID AND addDoc = :num ";
  /*if($sort == "alphabetical"){
    $sql .= "ORDER BY Name ASC";
  }elseif($sort == "DueDate"){
    $sql .= "ORDER BY cast(DueDate as datetime) ASC";
    echo "duedate";
  }elseif($sort == "createASC"){
    $sql .= "ORDER BY cast(CreateDate as datetime) ASC";
    echo "createAsc";
  }elseif($sort == "createDESC"){
    $sql .= "ORDER BY cast(CreateDate as datetime) DESC";
    echo "createDESC";
  }else{

  }*/

  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':ID',$id);
  $stmt->bindValue(':num', $x);
  $stmt->execute();
  while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($Objarray, Documents::find_by_id_prepared($record['DocID']));
  }
  return $Objarray;

}

public function departmentName($CatID){
  $sql = "SELECT Dept.Name AS Name FROM Dept ";
  $sql .= "INNER JOIN Category ON Category.Dept = Dept.ID ";
  $sql .= "WHERE Category.ID = :catId";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':catId',$CatID);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $deptName = $record['Name'];
  return $deptName;
}


public function getUsers($cat){ //gets the users that match the category
  $userArray = array();
  $users = static::find_all_prepared();
  foreach($users as $use){
    $cat2 = parse($use->Cat);
    foreach($cat2 as $catin){
      foreach($cat as $cater){
        if($catin == $cater){
          array_push($userArray, $use);
        }
      }
    }
  }
  $result = array_unique($userArray, SORT_REGULAR);
  return $result;
}

public function catName($cat){
  $parsed = parse($cat);
  $x = sizeof($parsed);
  $i = 0;
  $j = 1;
  $sql = "SELECT Description From Category "; //could change this to take all if that is better.
  $sql .= "WHERE ";
  $sql .= "(";
  foreach($parsed as $cat){
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
  foreach($parsed as $cat){
    $stmt->bindValue($j,$cat,PDO::PARAM_INT);
    $j = $j+1;
  }
  $stmt->execute();
  $catNames = array();

  while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($catNames,$record['Description']);
  }
  return $catNames;
 }

 function programName($id){
   $sql = "SELECT Name FROM Program WHERE ID= :id ";
   $stmt = self::$database->prepare($sql);
   $stmt->bindValue(':id',$id);
   $stmt->execute();
   $record = $stmt->fetch(PDO::FETCH_ASSOC);
   return $record['Name'];
 }

 static public function count_all_prepared(){
   $sql = "SELECT COUNT(*) FROM " . static::$table_name;
   $stmt = self::$database->prepare($sql);
   $stmt->execute();
   $row = $stmt->fetch();
   return array_shift($row);
 }

 public function paginate($require,$sort){ //this function is used for pagination. I believe the way it is set up it will load everything in the home pages already. Was hoping to not do that for speed reasons
   if($require == NULL){
     return $require;
   }
   $reqer = array();
   $sql = "SELECT ID FROM Docs ";
   $sql .= "WHERE ";
   $sql .= "(";
   $i = 0;
   $j = 1;
   foreach($require as $req){ //require is the array of docs
     $i = $i+1;
     if($i == $this->total_count){ //total count is the total number of docs
       $sql .= "ID= ? ";
     }else{
       $sql .= "ID = ? ";
       $sql .= " OR ";
     }
   }
   $sql .= ") ";
   if($sort == "alphabetical"){
     $sql .= "ORDER BY Name ASC ";
   }elseif($sort == "DueDate"){
     $sql .= "ORDER BY cast(DueDate as datetime) ASC ";
   }elseif($sort == "createASC"){
     $sql .= "ORDER BY cast(CreateDate as datetime) ASC ";
   }elseif($sort == "createDESC"){
     $sql .= "ORDER BY cast(CreateDate as datetime) DESC ";
   }else{
     $sql .= "ORDER BY ID ";
   }
   $sql .= "OFFSET ? ROWS ";
   $sql .= "FETCH NEXT ? ROWS ONLY";
   $stmt = self::$database->prepare($sql);
   foreach($require as $req){
     $stmt->bindValue($j,$req->ID,PDO::PARAM_INT);
     $j = $j+1;
   }
   $stmt->bindValue($j+1,$this->per_page,PDO::PARAM_INT);
   $stmt->bindValue($j,$this->offset(),PDO::PARAM_INT);
   $docs = Documents::find_by_stmt($stmt);
   foreach($docs as $doc){
     $dock = Documents::find_by_id_prepared($doc->ID);
     array_push($reqer, $dock);
   }
   return $reqer;
 }

}

?>
