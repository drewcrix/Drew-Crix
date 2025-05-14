
<?php require_once('../../../private/initialize.php');


//Just include the full path to the header for now then can worry about scalability later

 require_login();
 if(isset($_POST['sort'])){
   $sortBy = $_POST['sort'];
 }elseif(isset($_GET['sort'])){
   $sortBy = $_GET['sort'];
 }else{
   $sortBy = '';
 }
 $id = $_GET['id'];
 $admin = User::find_by_id_prepared($id);
 $namesplit = spacesplit($admin->UName);
 $firstname = $namesplit[0];
 adminstatus($admin);
 $docarr = $admin->adminHome_prepared($id);
 $befrefineArray = array();

//refine array algorithm
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
         array_push($befrefineArray, $doc);
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
       array_push($befrefineArray,$docarr[$i]);
     }
   }

 }

  $current_page = $_GET['page'] ?? 1;
  $per_page = 5;
  $total_count = sizeof($befrefineArray);
  $pagination = new Pagination($current_page, $per_page, $total_count);
  $refineArray = $pagination->paginate($befrefineArray,$sortBy);



?>
<head>
  <title class=  "title">Visual Communication Tracker - My Admin View</title>
    <div class="grid-contain">
      <div class="child-title">
        <h1 class=  "title"><img src = "/shared/adminView.png" height = 40px width = 40px />My Admin View</h1>
      </div>
      <div class="child-button">
        <div class="left">
          <a class = "profile" href="<?php echo url_for("../../webpages/My_Profile.php?id=" . h(u($id))) ; ?>"><img src="/shared/profileWhite.png" height = 40px width = 40px></a>
          <p class = "p">My Profile</p>
        </div>
        <div class="right">
          <a class = "logoff" href="<?php echo url_for("../../webpages/logout.php?id=" . h(u($id))) ; ?>"><img src="/shared/LOGOFFWhite.png" height = 40px width = 40px></a>
          <p class = "p">Log Off</p>
        </div>
    </div>
    </div
  <meta charset="utf-8">
  <link rel="stylesheet" media="all" href="<?php echo url_for("../../stylesheets/headerstyle.css") ; ?>" />
</head>
<p><i>Hi <b><?php echo $admin->DisplayName ; ?></b>, welcome to your communication tracker!</i></p>
<div class="containor">
  <div id="flex-item">
    <a class="imgcenter" href="<?php echo url_for('../../webpages/user_webpages/home.php?id=' . h(u($id))); ?>"><img src="/shared/trackerBlue.png"  height = 100px width = 100px  alt="tracker page"></a>
    <p class="center">My Tracker</p>
  </div>
  <div id="flex-item">
    <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/global.php?id='. h(u($id)));?>"><img src="/shared/globalblue.png"  height = 100px width = 100px alt="Global Tracker"></a>
    <p class="center">Global Tracker</p>
  </div>
  <div id="flex-item">
    <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/new.php?id='. h(u($id)));?>"><img src="/shared/newBlue.png"  height = 100px width = 100px alt="new doc"></a>
    <p class="center">Add New</p>
  </div>
  <div id="flex-item">
    <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/editPage.php?id='. h(u($id)));?>"><img src="/shared/editBlue.png" height = 100px width = 100px alt="edit existing doc"></a>
    <p class="center">Edit Existing</p>
  </div>
  <div id="flex-item">
    <a class="imgcenter" href="<?php echo url_for('../../webpages/admin_webpages/settings.php?id='. h(u($id)));?>"><img src="/shared/settings.png"  height = 100px width = 100px alt="admin settings"></a>
    <p class="center">Admin Settings</p>
  </div>
</div>

<div id="main">
  <h1>My Communications</h1>
  <form id = "sorter" class="sort" action="<?php echo url_for('../../webpages/admin_webpages/home.php?id='. h(u($id))); ?>" method = "POST">
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

  <table id=homeadmin>

    <tr>
      <th>Title</th>
      <th>Date Created</th>
      <th>Status</th>
      <th>Update</th>
    </tr>
    <?php foreach($refineArray as $doc){ ?>
    <tr>
      <td><?php echo $doc->Name ; ?></td>
      <td><?php echo $doc->CreateDate ; ?></td>
      <?php if($doc->Publish == 1){ ?>
        <td>Draft</td>
        <td><a class= "readtheUP" href="<?php echo url_for("../../webpages/admin_webpages/editResume.php?id=" . h(u($doc->ID)) . '&userID=' . h(u($id))) ; ?>">Resume</a></td>
      <?php }else{ ?>
        <td>Published</td>
        <td> </td>
      <?php } ?>

    </tr>
  <?php } ?>

  </table>

</div>
<?php
$url = url_for('../../webpages/admin_webpages/home.php?id=' . h(u($id)) . '&sort=' . h(u($sortBy)));
echo $pagination->page_links($url);
?>
