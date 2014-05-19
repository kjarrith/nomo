<?php
$file = "http://www.nomo.is/docs/skil.pdf"; 

header("Content-Description: File Transfer"); 
header("Content-Type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=\"$file\""); 

readfile ($file); 
?>