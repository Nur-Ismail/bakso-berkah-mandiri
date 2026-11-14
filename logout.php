<?php
session_start();
// Hancurkan semua data sesi
$_SESSION = array();
session_destroy();
// Tendang balik ke login
header("Location: login.php");
exit;
?>