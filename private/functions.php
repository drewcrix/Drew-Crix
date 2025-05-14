<?php



function url_for($script_path) {
  // add the leading '/' if not present
  if($script_path[0] != '/') {
    $script_path = "/" . $script_path;
  }
  return WWW_ROOT . $script_path;
}

function u($string="") {
  return urlencode($string);
}

function raw_u($string="") { //i will use this one and it is used to confert a string into valid url. <a href="insert url",rau_u($string) '">"'
  return rawurlencode($string);
}

function h($string="") {
  return htmlspecialchars($string);
}

function error_404() {
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit();
}

function error_500() {
  header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
  exit();
}

function redirect_to($location) {
  header("Location: " . $location);
  exit;
}

function is_post_request() {
  return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
  return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function require_login(){
  global $session;
  if(!$session->is_logged_in()){
    redirect_to(url_for('../../index.php'));
  }else{
    //do nothing let rest of page proceed
  }
}

function parse($CatID){ //takes the string data and parse into an array of integers which will represent the catagories that the document will be assigned too
  $CatID_array = explode(",",$CatID);
  return $CatID_array;
}

function adminstatus($person){
  if($person->Status != 1){

  }else{
    redirect_to(url_for('/webpages/user_webpages/home.php?id=' . h(u($person->ID))));
  }
}
function display_errors($errors){
  foreach($errors as $key=>$value){
    echo $value;
  }
}

function is_blank($value) {
    return !isset($value) || trim($value) === '';
}

function spacesplit($Name){
  $split = str_split($Name);
  $first = array();
  $last = array();
  $fullname = array();
  for($i = 0; $i < sizeof($split); $i++){
    if($split[$i] == ' '){
      $x = $i;
    }
  }
  for($y = 0; $y < $x; $y++){
    array_push($first, $split[$y]);
  }
  for($z = $x; $z < sizeof($split); $z++){
    array_push($last, $split[$z]);
  }
  $firstname = implode("",$first);
  $secondname = implode("",$last);
  array_push($fullname, $firstname, $secondname);

  return $fullname;


}

function namespaceSplit($user){
  $split = str_split($user);
  $name = array();
  for($i = 0; $i < sizeof($split); $i++){
    if($split[$i] == ' '){
      $x = $i;
    }
  }
  for($y = 0; $y < $x; $y++){
    array_push($name, $split[$y]);
  }
  $username = implode("",$name);
  return $name;
}



// PHP on Windows does not have a money_format() function.
// This is a super-simple replacement.
if(!function_exists('money_format')) {
  function money_format($format, $number) {
    return '$' . number_format($number, 2);
  }
}

function adminDepart($ID, $database){
  $sql = "SELECT Cat FROM Usertable WHERE ID='" . $ID . "'";
  $result = $database->query($sql);
  $record = $result->fetch(PDO::FETCH_ASSOC);
  $parsed = parse($record["Cat"]);
  $sql2 = "SELECT TOP 1 Dept FROM Category WHERE ID='" . $parsed[0] . "'";
  $result2 = $database->query($sql2);
  $record2 = $result2->fetch(PDO::FETCH_ASSOC);
  return departdescript($record2['Dept'], $database);

}

function departdescript($id,$database){
  $sql = "SELECT * FROM Dept ";
  $sql .= "WHERE ID='" . $id . "'";
  $result = $database->query($sql);
  $record = $result->fetch(PDO::FETCH_ASSOC);
  return $record['Name'];
}

function CatIDquarry($department, $database){
  $array = array();
  $sql = "SELECT Category.Description AS Descript FROM Category ";
  $sql .= "INNER JOIN Dept ON Category.Dept = Dept.ID ";
  $sql .= "WHERE Dept.Name='" . $department . "'";
  $result = $database->query($sql);
  while($record = $result->fetch(PDO::FETCH_ASSOC)){
    array_push($array, $record['Descript']);
  }
  return $array;
}

function adminDepart_prepared($ID, $database){
  $i = 0;
  $j = 1;
  $sql = "SELECT Cat FROM Usertable WHERE ID= :ID";
  $stmt = $database->prepare($sql);
  $stmt->bindValue(':ID',$ID);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  $parsed = parse($record["Cat"]);
  $x = sizeof($parsed);
  if($parsed == NULL){return "no department"; }
  $sql2 = "SELECT Dept FROM Category ";
  $sql2 .= "WHERE ";
  $sql2 .= "(";
  foreach($parsed as $cat){
    $i = $i+1;
    if($i == $x){
      $sql2 .= "ID= ? ";
    }else{
      $sql2 .= "ID = ? ";
      $sql2 .= " OR ";
    }

  }
  $sql2 .= ") ";
  $stmt2 = $database->prepare($sql2);
  foreach($parsed as $cat){
    $stmt2->bindValue($j,$cat,PDO::PARAM_INT);
    $j = $j+1;
  }
//  $stmt2->bindValue(':parse',$parsed[0]);
  $stmt2->execute();
  $arrayDept = array();
  //$record2 = $stmt2->fetch(PDO::FETCH_ASSOC);
  //return departdescript_prepared($record2['Dept'], $database);
  while($record2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
    array_push($arrayDept,$record2['Dept']);
  }
  $true_array = array_unique($arrayDept);
  $return = array();
  foreach($true_array as $arr2){
    $x = departdescript_prepared($arr2, $database);
    array_push($return, $x);
  }
  return $return;
}

function departdescript_prepared($id,$database){
  $sql = "SELECT * FROM Dept ";
  $sql .= "WHERE ID= :ID";
  $stmt = $database->prepare($sql);
  $stmt->bindValue(':ID',$id);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  return $record['Name'];
}

function CatIDquarry_prepared($department, $database){
  $array = array();
  $i = 0;
  $j = 1;
  if(sizeof($department) == 1){
    $x = $department[0];
  }
  $sql = "SELECT Category.Description AS Descript FROM Category ";
  $sql .= "INNER JOIN Dept ON Category.Dept = Dept.ID ";
  $sql .= "WHERE ";
  $sql .= "(";
  foreach($department as $depart){
    $i = $i+1;
    if($i == sizeof($department)){
      $sql .= "Dept.Name= ? ";
    }else{
      $sql .= "Dept.Name = ? ";
      $sql .= " OR ";
    }

  }
  $sql .= ") ";
  $stmt = $database->prepare($sql);
  if($x != NULL){
    $stmt->bindValue(1,$x);
  }else{
    for($y = 1; $y <= sizeof($department); $y++){
      $stmt->bindValue($y,$department[$y-1]);
    }
  }
  $stmt->execute();
  while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($array, $record['Descript']);
  }
  return $array;
}

function catIDtaker($descript, $database){
  $sql = "SELECT ID FROM Category WHERE Description= :Descript ";
  $stmt = $database->prepare($sql);
  $stmt->bindValue(':Descript',$descript);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  return $record['ID'];
}


?>
