<?php //code for parallel

$rt = new \parallel\Runtime();

$rt->run(function(){
 for ($i = 0; $i < 50; $i++)
 echo "+";
});

print_r("hello");
for ($i = 0; $i < 50; $i++) {
 echo "-";
}
$rt->kill();
?>
