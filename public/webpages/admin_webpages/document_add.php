//this page is going to requrie that the user has admin access and if the user does not have admin acces the website will redirect saying no access is available
<?php $page_title = 'Update Existing Communication Dissemination'; ?> //set the header to the page title


<div id="content">
  <h1>Log in</h1>

  <?php echo display_errors($errors);//do the styling later ?>

  <form action="document_add.php" method="post">
    Title:<br />
    <input type="text" name="Title" value="<?php echo h($title); ?>" /><br />
    Link:<br />
    <input type="url" name="hyperlink" value="<?php echo h($hyperlink); ?>" /><br />
    Dept: <br/>
    <?php $result = departmentquarry(); ?>
      <select>
        <?php foreach($result as $depart){?> //create a function that runs an sql query on the table and then make a drop down list with whatever is in the table of departments.
          <option>$depart</option>//should be a drop down list that is populated with whatever is in the table
      <?php } ?>
    <input type="submit" name="Sign In" value="Sign In"  />
    <div class = "pass" >Forgot Password?</div>
  </form>

</div>

<?php



 ?>
