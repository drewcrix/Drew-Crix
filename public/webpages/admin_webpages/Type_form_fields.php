<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($Type)) {
  redirect_to(url_for('../../webpages/admin_webpages/home.php'));
}
?>


  <label>Name:</label>
  <input id = "first" type="text" name="Type[Description]" value="<?php echo h($Type->Description); ?>" /><br><br>

  <label>Description:</label>
  <input id = "first"  type="text" name="Type[detail]" value="<?php echo h($Type->Detail); ?>" /><br>
