<?php
function unithai($s) 
{ 
$x = ""; 
$len = strlen($s); 
for ( $i = 0; $i < $len; $i++) 
{ 
if ( ord($s[$i]) > 128 ) 
$x .= "&#".(ord($s[$i]) - 160 + 3584).";"; 
else 
$x .= $s[$i]; 
} 
return $x; 
} 

$date = "วันที่";
$font = unithai($date);

echo $date."aa";
?>