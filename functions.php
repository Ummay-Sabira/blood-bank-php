<?php
// functions.php
include "database.php"; // Include the database connection

// Fetch blood groups from the database
function fetchBloodGroups($conn) {
    $query = "SELECT * FROM BloodGroups";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Insert a new donor into the database
function insertDonor($conn, $fullName, $mobileNumber, $emailId, $gender, $age, $bloodGroupID, $district, $password) {
    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO BloodDonors (fullName, mobileNumber, emailId, gender, age, bloodGroupID, district, password, adminID) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'ssssissssi', $fullName, $mobileNumber, $emailId, $gender, intval($age), intval($bloodGroupID), $district, $hashedPassword, $_SESSION['adminID']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true; // Success
    } else {
        return false; // Failure
    }
}
?>