<?php
session_start();
session_destroy(); 
header("Location: /kalam/index.php");
exit();
?>
