<?php
session_start();
session_destroy();
header("Location: index.php"); // landing page
exit();
?>
