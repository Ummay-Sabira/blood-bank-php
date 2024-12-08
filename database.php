<?php
// Database connection details
$db_server = "localhost";  // Use localhost since it's the correct host
$db_user = "root";         // Use the root user
$db_pass = "";             // Empty password (default for root in many setups)
$db_name = "Bbank";        // Your database name

// Create connection

// Check connection
try{
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    
}
catch(mysqli_sql_exception){
    echo "couldn't connect!";

}


?>
