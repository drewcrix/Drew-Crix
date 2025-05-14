<?php

require_once('../../../private/initialize.php');



require_login();

if(isset($_POST['sort'])){
  $sortBy = $_POST['sort'];
}elseif(isset($_GET['sort'])){
  $sortBy = $_GET['sort'];
}else{
  $sortBy = '';
}
$id = $_GET['id'];
$user = User::find_by_id_prepared($id);


$cat = parse($user->Cat);
$adminUsers = $user->getUsers($cat);
for($i=0; $i < sizeof($adminUsers); $i++){
  if($adminUsers[$i]->UName == $user->UName){
    unset($adminUsers[$i]);
  }
}


//$deptName = adminDepart_prepared($id, $database); //taking in $ID of the user thats on this page
//$docs = Docs::findDeptDocs($deptName); //all of the docs that connect to same dept
$userdocs_id = new User_Doc($id, $sortBy);

$docarr = $userdocs_id->adminHome_prepared($id);
$befrequire = array();
$beforerequire = array();


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
  }
}

$current_page = $_GET['page'] ?? 1;
$per_page = 3;
$total_count = sizeof($befrequire);
$pagination = new Pagination($current_page, $per_page, $total_count);

$require = $pagination->paginate($befrequire,$sortBy);

//I have all documents and users that I need in the form of objects and in this case I have update my duplicate so that it won't delete if docID is same but userID is different
?>
<head>
  <title class=  "title">Visual Communication Tracker - Global Tracker</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = /shared/globalWhite.png height = 40px width = 40px />Global Tracker</h1>
      </div>
      <div class="child-button">
        <div class="left">
          <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php") ; ?>"><img src="/shared/profileWhite.png" height = 40px width = 40px></a>
          <p class = "p">My Profile</p>
        </div>
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php") ; ?>"><img src="/shared/LOGOFFWhite.png" height = 40px width = 40px></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
    </div
  <meta charset="utf-8">
<link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>

<p><i>Hi <b><?php echo $user->DisplayName; ?></b>, welcome to your communication tracker!</i></p>
<div id='main'>
  <form id = "sorter" class="sort" action="<?php echo url_for('../../webpages/admin_webpages/global.php?id=' . h(u($id))); ?>" method = "POST"> <?php //grabs the get method and stores the data on this page. IF this doesn't work then keep the sort in the url?>
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

  <table id="global">
    <tr>
      <th class="nobackground">Name</th>
      <?php foreach($require as $doc){?>
        <th class="rotate"><?php echo $doc->Name; ?></th>
  <?php } ?>
    </tr>
  <?php foreach($adminUsers as $us){?>
    <tr>
      <td class="globalname"><?php echo $us->UName; ?></td>
      <?php foreach($require as $doc){ //check the status of each user at each doc and echo that in the rows?>
        <?php $status = $userdocs_id->return_status_prepared($doc->ID, $us->ID); ?>
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
        <?php }elseif($status == NULL){ ?>
          <td></td>
      <?php } ?>
 <?php } ?>
  </tr>
<?php } ?>

</table>

</div>
<?php
$url = url_for('../../webpages/admin_webpages/global.php?id=' . h(u($id)) . '&sort=' . h(u($sortBy)));
echo $pagination->page_links($url);
?>
