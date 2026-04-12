<?php

$content = file_get_contents('php://input');
// Parse JSON
file_put_contents("textdialogflow.txt", $content);
$events = json_decode($content, true);



?>