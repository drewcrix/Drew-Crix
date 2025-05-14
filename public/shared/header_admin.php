<!doctype html>
<html lang="en">
  <head>
    <title>Visual Communication Tracker<?php if(isset($page_title)) { echo '- ' . h($page_title); } ?></title>
    <?php if(isset($page_title) AND $page_title != "My Profile") { ?>
      <div class="grid-contain">
        <div class="child-profile">
          <a href=<?php echo url_for("C:\Users\acrix\Desktop\VCT\public\webpages\My_Profile.php?id=". h(u($id))) ?> target="_blank"><img src="C:\Users\acrix\Desktop\VCT\public\shared\profileWhite.png"></a>
        </div>
     }
        <div class="child-logoff">
          <a href=<?php echo url_for("C:\Users\acrix\Desktop\VCT\public\webpages\logout.php?id=". h(u($id))) ?> target="_blank"><img src="C:\Users\acrix\Desktop\VCT\public\shared\LOGOFFWhite.png"></a>
          <p>Log Off</p>
        </div>
      </div>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/public.css'); ?>" />
  </head>

<?php /*
    <header>
      <h1> //not totally sure if this will work cause not sure if admin is set on this page.DELETE COMMENT TO TEST
        Hi <?php echo $admin->name; ?>, welcome to your communication tracker
      <h1>
        <a href="<?php echo url_for('C:\Users\acrix\Desktop\VCT\public\webpages\admin_webpages\home.php'); ?>">My Tracker</a>
      </h1>
      <h1>
        <a href="<?php echo url_for('C:\Users\acrix\Desktop\VCT\public\webpages\admin_webpages\global.php');?>">Global Tracker</a>
      </h1>
      <h1>
        <a href="<?php echo url_for('C:\Users\acrix\Desktop\VCT\public\webpages\admin_webpages\new.php');?>">Add New</a>
      </h1>
      <h1>
        <a href="<?php echo url_for('C:\Users\acrix\Desktop\VCT\public\webpages\admin_webpages\edit.php');?>">Edit Existing</a>
      </h1>
      <h1>
        <a href="<?php echo url_for('C:\Users\acrix\Desktop\VCT\public\webpages\admin_webpages\settings.php');?>">Admin Settings</a>
      </h1>
      <h1>
        <a href = "<?php echo url_for('C:\Users\acrix\Desktop\VCT\public\webpages\logout.php');?>">Logout</a>
    </header>
*/ ?>
