<?php
session_start(); // Start session management
include("database.php");
include("header.php");
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'donor') {
    // Redirect unauthorized users to the home page
    header("Location: index.php");
    exit; // Stop script execution
}

// Initialize variables for error messages and current user info
$emailError = '';
$mobileError = '';
$currentUserId = $_SESSION['emailID'];

// Fetch current user data from the database
$query = "SELECT * FROM BloodDonors WHERE emailID = '$currentUserId'";
$result = mysqli_query($conn, $query);
$userData = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $mobileNumber = $_POST['mobileNumber'];
    $emailId = $_POST['emailID'];
    $password = $_POST['password'];
    $age = $_POST['age'];
    $district = $_POST['district'];
    $lastDonationDate = $_POST['lastDonationDate'];


    // Validate inputs
    if (!filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    }

    if (!preg_match('/^\d{11}$/', $mobileNumber)) {
        $mobileError = "Mobile number must be 11 digits";
    }

    // If no errors, proceed to update the data
    if (empty($emailError) && empty($mobileError)) {
        // Hash the password before saving (if provided)
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE BloodDonors SET fullName='$fullName', mobileNumber='$mobileNumber', emailID='$emailId', age='$age', district='$district', lastDonationDate='$lastDonationDate', password='$hashedPassword' WHERE emailID='$currentUserId'";
        } else {
            // Update without changing the password
            $updateQuery = "UPDATE BloodDonors SET fullName='$fullName', mobileNumber='$mobileNumber', emailID='$emailId', age='$age', district='$district', lastDonationDate='$lastDonationDate' WHERE emailID='$currentUserId'";
        }

       // if (mysqli_query($conn, $updateQuery)) {
           // echo "<p>Information updated successfully!</p>";
        //} else {
            //echo "<p>Error updating information: " . mysqli_error($conn) . "</p>";
       // }
       try  {
        mysqli_query($conn,$updateQuery);
        
       echo "<p>Information updated successfully!</p>";
        // Redirect to index.html after 2 seconds
        header("Refresh: 2; url=index.php"); 
        exit(); // Stop further execution after redirection
        
   
      
     } 
     catch(mysqli_sql_exception $e) {
        echo "re-enter data ".$e ;
     }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Information</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>

<body>
    <main>
        <h2>Update Your Information</h2>

        <div class="info-box"> <!-- Start of info box -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($userData['fullName']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="mobileNumber">Mobile Number:</label>
                    <input type="text" id="mobileNumber" name="mobileNumber" value="<?php echo htmlspecialchars($userData['mobileNumber']); ?>" required>
                    <span class="error"><?php echo $mobileError; ?></span>
                </div>

                <div class="form-group">
                    <label for="emailID">Email ID:</label>
                    <input type="email" id="emailID" name="emailID" value="<?php echo htmlspecialchars($userData['emailID']); ?>" required>
                    <span class="error"><?php echo $emailError; ?></span>
                </div>
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($userData['district']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="lastDonationDate">Last Donation Date:</label>
                    <input type="date" id="lastDonationDate" name="lastDonationDate" value="<?php echo htmlspecialchars($userData['lastDonationDate']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">New Password (Leave blank to keep current):</label>
                    <input type="password" id="password" name="password" minlength="6">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="button">Update</button>

            </form>
        </div>
        <br>
        <!-- Container with text-align: right for Right Alignment -->
        <div style="text-align: right;">
            <form action="logout.php" method="POST">
                <button type="submit" style="background-color: red; color: white; border: none; border-radius: 5px; text-decoration: none; padding: 10px 20px;" class="button">Logout</button>
            </form>
        </div>

    </main>

    <?php include 'footer.php'; ?>
</body>

</html>

<style>
    /* styles.css */

    /* Add this CSS to style the form */
    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type=text],
    input[type=email],
    input[type=password] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .error {
        color: red;
        /* Error message color */
    }

    .button {
        background-color: red;
        /* Button color */
        color: white;
        /* Text color */
        padding: 10px 20px;
        /* Padding around text */
        border: none;
        /* No border */
        border-radius: 5px;
        /* Rounded corners */
    }

    .button:hover {
        background-color: darkred;
        /* Darker shade on hover */
    }

    /* New CSS for the info box */
    .info-box {
        background-color: #f9f9f9;
        /* Light gray background */
        border: 1px solid #ccc;
        /* Gray border */
        border-radius: 10px;
        /* Rounded corners */
        padding: 20px;
        /* Space inside the box */
        margin-top: 20px;
        /* Space above the box */
    }
</style>