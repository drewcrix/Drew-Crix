<?php
//TEST 1 LOGIN
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

$errors = [];
$username = 'drewcrix';


if(User::is_blank($username)) {
  $errors[] = "Username cannot be blank.";
}

  // if there were no errors, try to login
if(empty($errors)) {
  $user = User::find_by_name($username);

}

    // test if admin found and password is correct
  if($user != false) { //if there is an admin and the password is verify then login the admin
      // Mark admin as logged in
    $session->login($user);
    User::require_admin_status($user); //need to get this to work.
  } else {
      // username not found or password does not match
    $errors[] = "Log in was unsuccessful.";
  }

// TEST 2 New Doc
  require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

  $args = array();
  $args['Name'] = "bruce lee fighterpack volume 6";
  $args['Hyperlink'] = 'www.google.com';
  $args['Dept'] = 'radiation';
  $args['Notify'] = 0;
  $args['CatID'] = 'first floor';
  $args['notifyperiod'] = '12';
  $args['day/monthnotify'] = 'weeks';
  $args['NotifyDate'] = "october 3rd";
  $args['Expiry'] = 1;
  $args['Type'] = 3;
  $args['DueDate'] = "april 12th";
  $args['ExpiryPeriod'] = 1;




  $document = new Documents($args);
  $result = $document->save_prepared(); //creates or updates with this line of code here.
  $docID = $document->ID;



  if($result == true) {
    $document->parentid_prepared($docID);
    $new_id = $docID;
  //  $session->message('The bicycle was created successfully.');
    redirect_to(url_for('/staff/bicycles/show.php?id=' . $new_id));
  } else {
    // show errors
  }

  //create date, Version, Parent_ID

  //echo var_dump($args);

//TEST 3 USER HOME PAGE
   require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

   //include(SHARED_PATH . '/header_user.php');

   //require_login();
  //should be able to use get now since the id is grabbed from the login directly. if this doesn't work try _SESSION['id']
  if(isset($_GET['sort'])){
    $sortBy = $_GET['sort'];
  }else{
    $sortBy = '';
  }
  $id = 1;
  $userdocs_id = new User_Doc($id,$sortBy); //if the save_prepared doesn't work try making an update and create function then do a find by id and if not found do create if found do update
  $require = $userdocs_id->object;
  foreach($require as $doc){
   echo $doc->Name;
   echo ' ';
   echo (Catagory::find_by_id($doc->CatID))->Description;
   echo ' ';
  }
  //TESTING if updating the status of the document function work? If the button is clicked is the document status updated
  $doc = $require[0];
  $DOCID = $doc->ID;
  $userdocs_id->updatestatus_prepared($DOCID); //This works now need to implement this with clicking the signoff button.
  $status = $userdocs_id->return_status_prepared($DOCID);
  echo $status;

  //TEST 5 creating the documents for the admin home page.
  require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');

  $id = 2;
  $admin = User::find_by_id($id);
  adminstatus($admin);
  $docarr = $admin->adminHome_prepared($id);
  foreach($docarr as $doc){
    var_dump($doc);
  }

  //TEST 6 checking to see if the variables can be used in the include file and they can.
  $x = 1;
  include("C:\Users\acrix\Desktop\VCT\public\webpages\admin_webpages\document_form_fields.php");



  //TEST 7 finding the document related to the user and this works
  require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');
  $id = 1;
  $department = adminDepart($id,$database); //$database is the PDO connection not an instance of the database class
  echo $department;
  $x = CatIDquarry($department, $database);
  foreach($x as $el){
    echo $el;
    echo ' ';
  }

  /*function adminDepart($ID,$database){
    $sql = "SELECT TOP 1 Category.Dept AS DeptID FROM Category "; //all categories should point to the same department
    $sql .= "INNER JOIN Usertable ON ";
    $sql .= "Usertable.Cat LIKE (select '%'+STR(Category.ID)+'%') "; //have to make this into one catID from the user class since all of them point to the same doc.
    $sql .= "WHERE Usertable.ID='" . $ID . "'";
    var_dump($sql);
    //$sql .= "LIMIT 1"; //LIMIT only works for mysql
    $result = $database->query($sql); //Cannot process this. Work on this nexttime.
    $record = $result->fetch(PDO::FETCH_ASSOC);
    var_dump($record['DeptID']);
    return departdescript($record['DeptID'],$database);

  }*/
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

// TESTING FOR THE EDIT PAGE
  $iddoc = 4; //in order for this edit document to work. Need to have a url for that includes the id in it
  $usID = 2;
  $document = Documents::find_by_id($iddoc); //finding the other attributes based on the id
  if($document == false) {
    redirect_to(url_for('/staff/bicycles/index.php'));
  }
    $document2 = new Documents($args);
    $document2->Parent_ID = $document->Parent_ID;
    $document2->Version = $document->Version + 1;
    $result = $document2->save_prepared();
    if($result == true) {
      $new_id = $document2->ID;
      $use_doc = new User_Doc($usID, $sort); //In this case a sort does not need to be done here a sort can be done on the admin home page where all documents with the userID of the admin will be querried and sorted
      $use_doc->adminAddDocs_prepared($document2, $usID); //save_prepareds the document that was just added by the admin onto the user_doc class.
      echo $new_id;
      //redirect_to(url_for('/staff/bicycles/show.php?id=' . $new_id));


  } else {

    // display the form

  }

  //TRYING find_by_name with prepared statment

  static public function find_by_name($name) {
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

  static public function find_by_stmt($stmt){ //sql gathered from subclasses of this function
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

//Algorithm for only outputing the most updated version of each document
for($i = 0; $i < sizeof($docarr); $i++){
  if($docarr[$i]->Version == 2){
    $parent = $docarr[$i]->Parent_ID;
    $versionArray = array();
    for($x = 0; $x < sizeof($docarr); $x++){
      if($docarr[$x]->Parent_ID == $parent){
        array_push($versionArray, $docarr[$x]->Version);
      }
    }
    $max = max($versionArray);
    foreach($docarr as $doc){
      if($doc->Version == $max AND $doc->Parent_ID == $parent){
        array_push($refineArray, $doc);
      }
    }
  }elseif($docarr[$i]->Version == 1){
    $parentOrg = $docarr[$i]->Parent_ID;
    $z = 0;
    for($x = 0; $x < sizeof($docarr); $x++){
      if($docarr[$x]->Parent_ID == $parentOrg){
        $z = $z+1;
      }
    }
    if($z==1){
      array_push($refineArray,$docarr[$i]);
    }
  }

}

//getting department name to make profile page.
$id = 1;

$user = User::find_by_id_prepared($id);
$user->DeptID = $user->departmentName($user->Cat[0]);

echo $user->UName;
echo $user->DeptID;

//checking if ldap connection works.
require_once('C:\Users\acrix\Desktop\VCT\private\functions\initialize.php');
$ldap = ldap_connect("SBADCRTP.sw.ca");

if(isset($ldap)){
  echo "hello";
}else{
  echo "tough luck";
}
//Seperate test to see if i can add a user onto the page with no premission.
$username = "bobjoe";
$newUser = new User;
$newUser->newUser($username); 
?>
