<?php
session_start();
session_destroy();
header("Location: loginDentro.php");
exit;
?>
