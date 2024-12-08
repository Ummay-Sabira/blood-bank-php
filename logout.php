<?php
// Start the session
session_start(); 

// Check if the user is logged in
if (isset($_SESSION['emailID'])) {
    // Unset all session variables
    $_SESSION = array(); 

    // If it's desired to kill the session, also delete the session cookie.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"], 
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy(); 

    // Redirect to home page after logout
    header("Location: index.php");
} else {
    // If no user is logged in, redirect to home page
    header("Location: index.php");
}
exit; // Ensure no further code is executed after redirection
?>