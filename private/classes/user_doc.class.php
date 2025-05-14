<?php
/*
the html page will create an instance of this class which will have all required information. Based on the id of the user.
will need to run a quary through all the documents and create an array when the catagories of the user and document matches as well as the department
when I find that then that DocID gets appened to the User_Doc table. Which can be used later to then find the doc hyperlink from the table
the the other data in the table can be appended fromt there.
*/
class User_Doc extends dataBasegather{ //going to make a class admin that extends user. //login sends person to home page with user id.
  static protected $table_name = 'User_Docs';
  static protected $db_columns = ['ID', 'UserID','DocID','Status','Status_Date','Version','Parent','addDoc']; //remember to add version here!

  public $ID; //these have to be written the same as the names from the column ID for it to be called when updating to table.
  public $UserID;
  public $DocID; //doc id will be a string of doc id per user.
  public $Status;
  public $Status_Date;
  public $catagories;
  public $Version;
  public $Parent;
  public $addDoc;
  public $object;

  public function __construct($id,$sortBy){
    $this->UserID = $id;
    $user = User::find_by_id_prepared($id);
    $this->catagories = parse($user->Cat);
    $this->Status = 1;
    $this->Status_Date = date("Y/m/d");
    $this->object = $this->findDocs_prepared($this->catagories,$sortBy,$user->Status);

  }

//When admin add the doc it will be added to database using this fuunction and assigned to there userID
  public function adminAddDocs_prepared($newDoc,$getID){ //to get the documents you would find by userID. Which would run a querry to return all the documents with the same userID. The userID is grabbed to GET super global.
    $this->DocID = $newDoc->ID;                 //the newDoc is the document that was recently save_preparedd into the database. In the case that this doesn't work. I can run a function that takes the most recently added document to the document database and then it will get the doc. Since this function will be called everytime an admin adds a new doc.
    $this->Version = $newDoc->Version;
    $this->UserID = $getID;
    $this->Parent = $newDoc->Parent_ID;
    $this->addDoc = 1;
    $this->save_prepared();
  }


  private function findDocs_prepared($cats,$sort,$status){ //gets the docs need for the my tracker page
    $x = sizeof($cats);
    $i = 0;
    $j = 1;
    $sql = "SELECT ID AS DocID,Version AS DocVersion,Parent_ID AS Parent FROM Docs "; //could change this to take all if that is better.
    $sql .= "WHERE ";
    $sql .= "(";
    foreach($cats as $cat){
      $i = $i+1;
      if($i == $x){
        $sql .= "CatID= ? ";
      }else{
        $sql .= "CatID = ? ";
        $sql .= " OR ";
      }

    }
    $sql .= ") ";
    if($sort == "alphabetical"){
      $sql .= "ORDER BY Name ASC";
    }elseif($sort == "DueDate"){
      $sql .= "ORDER BY cast(DueDate as datetime) ASC";
    }elseif($sort == "createASC"){
      $sql .= "ORDER BY cast(CreateDate as datetime) ASC";
    }elseif($sort == "createDESC"){
      $sql .= "ORDER BY cast(CreateDate as datetime) DESC";
    }else{

    }
    $stmt = dataBasegather::$database->prepare($sql);
    foreach($cats as $cat){
      $stmt->bindValue($j,$cat,PDO::PARAM_INT);
      $j = $j+1;
    }
    $stmt->execute();
    $array1 = array();
    $arrayObj = array();
    while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
      $this->DocID = $record['DocID'];
      $this->Version = $record['DocVersion'];
      $this->Parent = $record['Parent'];
      if($status == 1){
        $this->addDoc === 2;
      }elseif($status == 2){
        $this->addDoc === 0;
      }
      array_push($array1,$record['DocID']);
      $this->addDoc === 2;
      $this->save_prepared(); //make sure this only save_prepareds the documents that are new. So possibly run a delete function after save_prepared to save_prepared all documents that are the same. So if it save_prepareds the same ones again it will delete the ones that are the same
      unset($this->ID);
    } //record will give an associative array for each of the catagory ID
    //join user table with document mysql_list_tables
    foreach($array1 as $arr){
      $obj = Documents::find_by_id_prepared($arr);
      array_push($arrayObj,$obj);
    }
    $objanswer = $arrayObj;
    $this->deleteDuplicates_prepared();
    $stmt->closeCursor();
    return $objanswer; //Get this result then run a find_by_id for each document and run though the details
  }




  static public function updatestatus_prepared($DOCID,$num,$usID){ //call update from the instance. //Call updatestatus_prepared on the object instance that needs updating
    $sql = "UPDATE User_Docs"." ";
    $sql .= "SET Status_Date = :date";
    $sql .= ",";
    $sql .= "Status = :num ";
    $sql .= "WHERE DocID = :ID AND UserID = :usID";
    $stmt = dataBasegather::$database->prepare($sql);
    $stmt->bindValue(':date',date("Y/m/d"));
    $stmt->bindValue(':num',$num);
    $stmt->bindValue(':ID',$DOCID);
    $stmt->bindValue(':usID', $usID);
    $result = $stmt->execute();
    return $result;
  }

  static public function updatehold_prepared($DOCID,$num){
    $sql = "UPDATE User_Docs"." ";
    $sql .= "SET Status_Date = :date";
    $sql .= ",";
    $sql .= "Status = :num ";
    $sql .= "WHERE DocID = :ID";
    $stmt = dataBasegather::$database->prepare($sql);
    $stmt->bindValue(':date',date("Y/m/d"));
    $stmt->bindValue(':num',$num);
    $stmt->bindValue(':ID',$DOCID);
    $result = $stmt->execute();
    return $result;
  }

  public function return_status_prepared($id,$UsID){
    $sql = "SELECT Status FROM User_Docs WHERE DocID = :ID AND UserID = :UsID";
    $stmt = dataBasegather::$database->prepare($sql);
    $stmt->bindValue(':ID',$id);
    $stmt->bindValue(':UsID',$UsID);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    return $record['Status'];
  }
  private function deleteDuplicates_prepared(){ //deletes duplicate entries
    $sql = "WITH cte AS (SELECT *, ROW_NUMBER() OVER (PARTITION BY DocID,UserID,addDoc ORDER BY ID) AS row_num FROM User_Docs) DELETE FROM cte WHERE row_num > :num";
    $stmt = dataBasegather::$database->prepare($sql);
    $stmt->bindValue(':num',1);
    $result = $stmt->execute();
    return $result;
  }

}



  //now you want to append each of those documents to the user_doc table


?>
