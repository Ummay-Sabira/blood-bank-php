<?php
session_start();
echo "logged out"; // Start session management
session_destroy(); 
// Destroy all session data
header('Location: index.php'); // Redirect to home page
exit; // Ensure no further code is executed after redirect
?>