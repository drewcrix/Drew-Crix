<?php
//checking if ldap connection works.

$ldap = ldap_connect("SBADCRTP.sw.ca");

if(isset($ldap)){
  echo "hello";
}else{
  echo "tough luck";
}
//Seperate test to see if i can add a user onto the page with no premission.


?>
