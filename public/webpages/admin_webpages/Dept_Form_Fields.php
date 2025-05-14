<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($deptadd)) {
  redirect_to(url_for('../../webpages/admin_webpages/home.php'));
}
?>


  <label>Department Name::</label>
  <input id="first" type="text" name="Dept[Name]" value="<?php echo h($deptadd->Name); ?>" /><br>


  <label>Assigned Programs:</label>
  <select name="Dept[Program]">
    <?php $progrm = Program::find_all_prepared();
    foreach($progrm as $pro){?>
      <?php if($pro->ID == $deptadd->Program){ ?>
               <option value="<?php echo $pro->ID; ?>" selected><?php echo $pro->Name; ?></option>
      <?php }else{ ?>
               <option value="<?php echo $pro->ID; ?>"><?php echo $pro->Name; ?></option>
    <?php } ?>

  <?php } ?>
  </select><br>
