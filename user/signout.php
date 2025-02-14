<?php
session_start();
session_destroy(); 
header("Location: /event/index.php");
exit();
?>
