<?php
session_start();

// Remove all session data
$_SESSION = [];
session_unset();
session_destroy();

// Redirect back to home page
header("Location: index.php");
exit;
