<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

header("Location: index.php");
exit;
?>
