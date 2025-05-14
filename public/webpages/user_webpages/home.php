<?php require_once('../../../private/initialize.php');

 //include(SHARED_PATH . '/header_user.php');

 require_login(); //NOW the user will only see documents that are published and that are the newest version.
//should be able to use get now since the id is grabbed from the login directly. if this doesn't work try _SESSION['id']
 if(isset($_POST['sort'])){
   $sortBy = $_POST['sort'];
 }elseif(isset($_GET['sort'])){
   $sortBy = $_GET['sort'];
 }else{
   $sortBy = '';
 }

 $id = $_GET['id']; //check to see if this get id thing makes sense or not. This should work since it grabs the id of the user that is logged in
 $uN = User::find_by_id_prepared($id);

 if($uN->Cat == '7'){
   exit("contact supervisor for premissions");
 }
 $namesplit = spacesplit($uN->UName);
 $firstname = $namesplit[0];
 $userdocs_id = new User_Doc($id, $sortBy); //if the save_prepared doesn't work try making an update and create function then do a find by id and if not found do create if found do update
 //$publish = $userdocs_id->object;
 $docarr = $userdocs_id->object;
// $docarr = array();
 $beforerequire = array();
 $befrequire = array();
/* foreach($publish as $pub){
   if($pub->Publish == 2){
     array_push($docarr, $pub);
   }elseif(date() > $pub->DueDate){
     $new = 4; //status = 4 means its past the due date
     $result = User_Doc::updatestatus_prepared($pub->ID, $new, $id);
   }
 }*/

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
         array_push($beforerequire, $doc);
       }
     }
   }elseif($docarr[$i]->Version == 1){
     //echo "hello";
     $parentOrg = $docarr[$i]->Parent_ID;
     //echo $parentOrg;
     $z = 0;
     for($x = 0; $x < sizeof($docarr); $x++){
       if($docarr[$x]->Parent_ID == $parentOrg){
         $z = $z+1;
       }
     }
     if($z==1){
       array_push($beforerequire,$docarr[$i]);
     }
   }

 }

 foreach($beforerequire as $pub){
    if($pub->Publish == 2){
      array_push($befrequire, $pub);
    }elseif(date() > $pub->DueDate){
      $new = 4; //status = 4 means its past the due date
      $result = User_Doc::updatestatus_prepared($pub->ID, $new, $id);
    }
  }

 $current_page = $_GET['page'] ?? 1;
 $per_page = 10;
 $total_count = sizeof($befrequire);
 $pagination = new Pagination($current_page, $per_page, $total_count);

 $require = $pagination->paginate($befrequire,$sortBy);


 $user = User::find_by_id_prepared($id);
 $catname = $user->Cat;
 $past_due = array();
 $pending = array();
 $optional = array();
 $onHold = array();
 $complete = array();



 if(isset($_POST['DOCusername']) AND isset($_POST['DOCpassword'])){//does this work?
   $ldap = ldap_connect("SBADCRTP.sw.ca");
   $username = $_POST['DOCusername'];
   $userldap = 'S&WCHSC\\'.$username;
   $password = $_POST['DOCpassword'];

   try{
     if($bind = ldap_bind($ldap, $userldap, $password) && $username == $uN->UName){
       $DOCID = $_GET['DocID'];
       $new = 2;
       $userdocs_id->updatestatus_prepared($DOCID,$new,$id); //i want the status of the specific document to update
     }else{
       throw new Exception;
     }
   }catch (Exception $x){
     echo "Wrong username or password";
   }

 }else{
   //do nothing
 }



 foreach($require as $doc){
    $statuses = $userdocs_id->return_status_prepared($doc->ID,$id);
    if(date("Y-m-d") > $doc->DueDate){
      if($statuses != 2){
        $new = 4; //status = 4 means its past the due date
        $result = User_Doc::updatestatus_prepared($doc->ID, $new,$id); //need to run this everywhere each time this is popped up.
        $statuses = $userdocs_id->return_status_prepared($doc->ID,$id);
      }
    }
    if($statuses == 1){
      array_push($pending, $statuses);
    }elseif($statuses == 3){
      array_push($onHold,$statuses);
    }elseif($statuses == 4){
      array_push($past_due,$statuses);
    }elseif($statuses == 5){
      array_push($optional,$statuses);
    }elseif($statuses == 2){
      array_push($complete,$statuses);
    }
 }

 $PastDue = sizeof($past_due); //these are used to dot he top thing on the my tracker
 $Pend = sizeof($pending);
 $Option = sizeof($optional);
 $Hold = sizeof($onHold);
 $completed = sizeof($complete);
 $myTeams = $user->catName($catname);


 //can I get an arrray of objects for each docID then I want to Docs::find_by_id for each of the objects


 ?>
 <html lang="en">
   <head>
     <title class=  "title">Visual Communication Tracker - My Tracker</title>
       <div class="grid-contain">
         <div class="child-title">
           <h1 class=  "title"><img src = "/shared/tracker.png" height = 40px width = 40px />My Tracker</h1>
         </div>
         <div class="child-button">
           <?php if($uN->Status == '2'){ ?>
            <div class = "lefter">
              <a class= "myAdmin" href="<?php echo url_for("../../webpages/admin_webpages/home.php?id=" . h(u($id))) ; ?>"><img src="/shared/adminView.png" height = "40px" width = "40px"></a>
              <p class = "p">Home</p>
            </div>
        <?php } ?>
           <div class="left">
             <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php?id=" . h(u($id))) ; ?>"><img src="/shared/profileWhite.png" height = "40px" width = "40px"></a>
             <p class = "p">My Profile</p>
           </div>
           <div class="right">
             <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php?id=" . h(u($id))) ; ?>"><img src="/shared/LOGOFFWhite.png" height = "40px" width = "40px"></a>
             <p class = "p">Log Off</p>
           </div>
       </div>
       </div
     <meta charset="utf-8">
     <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
   </head>

   <p><i>Hi <b><?php echo $uN->DisplayName ; ?></b>, welcome to your communication tracker!</i></p>

   <div class="containor">
     <div id="flex-item">
       <p id = flexP-red>Past Due Items</p>
       <p id = flexPN-red><?php echo $PastDue; ?></p>
     </div>
     <div id="flex-item">
       <p id = "flexP-lightgreen">Pending Items</p>
       <p id = flexPN><?php echo $Pend; ?></p>
     </div>
     <div id="flex-item">
       <p id = flexP-blue>Optional Items</p>
       <p id = flexPN><?php echo $Option; ?></p>
     </div>
     <div id="flex-item">
       <p id = flexP-yellow>On Hold Items</p>
       <p id = flexPN><?php echo $Hold; ?></p>
     </div>
     <div id="flex-item">
       <p id = flexP-lightgreen>Completed Items</p>
       <p id = flexPN><?php echo $completed; ?></p>
     </div>
     <div id="flex-item">
       <p id = flexP-gray>My Teams</p>
       <ul>
         <?php foreach($myTeams as $team){ ?>
           <li><?php echo $team;?></li>
         <?php } ?>
       </ul>
     </div>
   </div>

<div id="main">
  <form id= "sorter" class = "sort" action="<?php echo url_for('../../webpages/user_webpages/home.php?id=' . h(u($id))) ; ?>" method = "POST">
    <label for="sort">Sort By:</label>
    <select name="sort" id="sort" onchange="this.form.submit()">
      <option name="select sort method">Select Sorting Type</option>
      <option value="alphabetical">Alphabetical</option>
      <option value="createASC">Oldest to Newest</option>
      <option value="createDESC">Newest to Oldest</option>
      <option value="DueDate">Due Date</option>
    </select>
    <noscript><input type="submit" value ="submit"/></noscript>
  </form>

  <img id="legend" src=/shared/legend.PNG alt = "legend" />

  <table id=homeuser>
    <tr>
      <th>Title of Content</th>
      <th>Team</th>
      <th>Type</th>
      <th>Status</th>
      <th>Access Link</th>
    </tr>
    <?php foreach($require as $doc){ ?>
    <tr>
      <td><?php echo $doc->Name ; ?></td>
      <td><?php
      echo (Catagory::find_by_id_prepared($doc->CatID))->Description; //find by id from the catagory table and then get description from that
      ?></td>
      <td><?php echo $doc->docType(); ?></td>
      <?php $status = $userdocs_id->return_status_prepared($doc->ID,$id); ?>
      <?php if($status == 1){ ?>
        <td><img src= "/shared/pending.png" alt= "document pending completion" height = 30px width = 30px></td>
      <?php }elseif($status == 2){ ?>
        <td><img src= "/shared/completed.png" alt= "document completed" height = 30px width = 30px></td>
      <?php }elseif($status == 3){ ?>
        <td><img src= "/shared/onhold.png" alt= "document on Hold" height = 30px width = 30px></td>
      <?php }elseif($status == 4){ ?>
        <td><img src= "/shared/overdue.png" alt= "document overdue" height = 30px width = 30px></td>
      <?php }elseif($status == 5){ ?>
        <td><img src= "/shared/optional.png" alt= "document optional" height = 30px width = 30px></td>
      <?php } ?>
        <?php if($status != 2 && $status != 3){ ?>
          <td><a class = "readtheUP" href="<?php echo url_for('../../webpages/user_webpages/docView.php?hyperlink='  . h(u($doc->Hyperlink)) . '&UsID=' . h(u($id)) . '&DocID=' . h(u($doc->ID))) ; ?>" target = "_blank">Read the Update</a></td>

      <?php }else{ ?>
        <td> </td>
    <?php  } ?>
    </tr>
  <?php } ?>

  </table>

</div>

<?php
$url = url_for('../../webpages/user_webpages/home.php?id=' . h(u($id)) . '&sort=' . h(u($sortBy)));
echo $pagination->page_links($url);
?>
