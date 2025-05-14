<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($Team)) {
  redirect_to(url_for('/webpages\admin_webpages\home.php'));
}
?>


  <label>Team Name::</label>
  <input type="text" name="Team[Description]" value="<?php echo h($Team->Description); ?>" /><br><br>


  <label>Members:</label>
  <?php $users = User::find_all_prepared();?>
  <select name = "Team[Users][]" multiple> <?php //is stored as array?>
  <?php foreach($users as $use){ ?>
    <option value = "<?php echo $use->UName; ?>"><?php echo $use->UName;
    echo ' ';
    echo '(';
    $x = adminDepart_prepared($use->ID, $database);
    foreach($x as $arr){
      echo $arr;
      echo ',';
    }
    echo ')';
    ?></option>
  <?php } ?>
</select><br><br>

  <label>Select Department Groups</label>
  <?php $Depart = Department::find_all_prepared();?>
  <select name = "Team[Dept]">
    <?php foreach($Depart as $dept){ ?>
      <option value="<?php echo $dept->ID ; ?>"><?php echo $dept->Name ; ?></option>
    <?php } ?>

    <?php //the users will be assigned the one category?>
