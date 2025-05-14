<?php

class Documents extends dataBasegather { //going to make a class admin that extends user
  static protected $table_name = 'Docs';
  static protected $db_columns = ['ID', 'Name','Hyperlink','Dept','Notify','DueDate','Expiry','CreateDate','NotifyDate','Version','Parent_ID','CatID','NotifyPeriod','ExpiryPeriod','Type','Publish'];

  public $ID;
  public $Name;
  public $Hyperlink;
  public $Dept;
  public $Notify;
  public $DueDate;
  public $Expiry;
  public $CreateDate; //this may have to be in the construct part but not totally sure so try that if this doesn't work
  public $NotifyDate;
  public $Version;//when ever there is an update you would add one to the Version of that updated document
  public $Parent_ID; //the id of Version 1
  public $CatID;
  public $NotifyPeriod;
  public $Type;
  public $ExpiryPeriod;
  public $Publish;

public function __construct($args=[]){
  if($args['day/monthnotify'] === 'days'){
    $args['day/monthnotify'] = 1;
  }elseif($args['day/monthnotify'] === 'weeks'){
    $args['day/monthnotify'] = 7;
  }
  if(isset($args['Publish'])){
    $this->Publish = 2;
  }elseif(isset($args['Draft'])){
    $this->Publish = 1;
  }else{
    $this->Publish = '';
  }
  if($args['day/monthduedate'] === 'days'){
    $args['day/monthduedate'] = 1;
  }elseif($args['day/monthduedate'] === 'weeks'){
    $args['day/monthduedate'] = 7;
  }
  $this->Name = $args['Name'] ?? '';
  $this->Hyperlink = $args['Hyperlink'] ?? '';
  $this->Notify = $args['Notify'] ?? '';
  $this->DueDate = $args['DueDate'] ?? ''; //can it just be date from now in post. And then it will return a proper due date on the user.
  $this->Expiry = $args['Expiry'] ?? '';  //this will see if the expiry and add the time to current time and then check a getdate("Y/m/d")with the timestamp and if over time stamp it expires
  $this->NotifyDate = $args['NotifyDate'] ?? '';
  $this->NotifyPeriod = ((int)$args['notifyperiod'] * $args['day/monthnotify']) ?? '';
  $this->CreateDate = date("Y/m/d");
  $this->NotifyDate = date('Y/m/d', time()+(86400*$this->NotifyPeriod));
  $this->ExpiryPeriod = ((int)$args['sendeveryduedate'] * $args['day/monthduedate']) ?? '';
  $this->Version = 1;
  $this->Parent_ID = 0;
  $this->Dept = $this->updatedepart_prepared($args['Dept']) ?? ''; //this returns the id for the department
  $this->CatID = $this->updatecat_prepared($args['category'], $this->Dept) ?? ''; //this returns the id for the catagory.
  $this->Type = $this->updatetype_prepared($args['Type']) ?? '';

  }

 /*private function expire($expiry){
   $result = 60*60*24*($expiry*365)+time();
   $this->$expiry = getdate($result); //run this whenever a new document is made or updated and the expiry date will be posted on the user home page
 }*/

 private function notifying(){ //needs to check this class every day until date is reached.
   if(!empty($NotifyDate)){
     if(date("Y/m/d") == getdate($NotifyDate)){
       include('link to other code');//sending out the notification.
     }
   }
 }




public function parentid_prepared($ID){
  $sql = "UPDATE Docs"." ";
  $sql .= "SET Parent_ID = ? ";
  $sql .= "WHERE ID = ?";
  $stmt = dataBasegather::$database->prepare($sql);
  $stmt->bindParam(1, $ID,PDO::PARAM_INT);
  $stmt->bindParam(2,$ID,PDO::PARAM_INT);
  $stmt->execute();
  $sql2 = "SELECT Parent_ID FROM Docs WHERE ID= :ID";
  $stmt2 = dataBasegather::$database->prepare($sql2);
  $stmt2->bindValue(':ID',$ID);
  $stmt2->execute();
  $record = $stmt2->fetch(PDO::FETCH_ASSOC);
  $this->Parent_ID = $record['Parent_ID'];
}

public function findDeptDocs($Name){
  $docName = array();
  $sql = "SELECT Docs.Name AS Name FROM Docs ";
  $sql .= "INNER JOIN Dept ON Dept.ID = Docs.Dept ";
  $sql .= "WHERE Dept.Name = :dept";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':dept',$Name);
  $stmt->execute();
  while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
    array_push($docName, $record['Name']);
  }
  return $docName;
}

public function docType(){
  $Type = $this->Type;
  $sql = "SELECT Description FROM type WHERE ID= :type";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':type',$Type);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  return $record['Description'];
}

public function docDept(){
  $Dept = $this->Dept;
  $sql = "SELECT Name FROM Dept WHERE ID= :type";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':type',$Dept);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  return $record['Name'];
}

public function docTeam(){
  $Cat = $this->CatID;
  $sql = "SELECT Description FROM Category WHERE ID= :type";
  $stmt = self::$database->prepare($sql);
  $stmt->bindValue(':type',$Cat);
  $stmt->execute();
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  return $record['Description'];
}

public function reacurrence(){ //sets the reacurrence page. Error is thrown when reacurrence is selected that is a decimal number
  $expiry = $this->Expiry;
  if($expiry == '0'){
    return "None";
  }elseif($expiry == "0.5"){
    return "Six Months";
  }elseif($expiry == "1"){
    return "One Year";
  }elseif($expiry == "1.5"){
    return "18 Months";
  }elseif($expiry == "2"){
    return "Two Years";
  }elseif($expiry == "NULL"){
    return " ";
  }
}

}
  ?>
