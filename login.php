<?php
// Include database connection
include("database.php");
include "header.php";
session_start(); // Start session management

// Initialize variable for error message
$emailError = '';
$loginError = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $DonemailID = $_POST['emailID'];
    $Donpassword = $_POST['password'];

    // Validate email and password fields
    if (empty($DonemailID) || empty($Donpassword)) {
        $loginError = "Please enter both email ID and password.";
    } else {
        // Sanitize user input to prevent SQL injection
        $DonemailID = mysqli_real_escape_string($conn, $DonemailID);
        $Donpassword = mysqli_real_escape_string($conn, $Donpassword);

        // Query the database to check if the email matches
        $Donquery = "SELECT * FROM BloodDonors WHERE emailID = '$DonemailID' LIMIT 1";
        $Donresult = mysqli_query($conn, $Donquery);

        // Check if a matching record was found
        if (mysqli_num_rows($Donresult) > 0) {
            $row = mysqli_fetch_assoc($Donresult);

            // Verify the password against the hashed password stored in the database
            if (password_verify($Donpassword, $row['password'])) {
                // Login successful, set session variables
                $_SESSION['emailID'] = $row['emailID'];
                //$_SESSION['user_email'] = $row['emailID'];
                $_SESSION['user_role'] = 'donor';

                // Fetch last donation date and calculate the eligibility
                $lastDonationDate = $row['lastDonationDate'];
                $currentDate = date('Y-m-d'); // Get the current date
                $date1 = new DateTime($lastDonationDate); // Convert last donation date to DateTime object
                $date2 = new DateTime($currentDate); // Current date
                $interval = $date1->diff($date2); // Calculate the difference

                // Check if the last donation date is greater than 56 days ago
                $isEligible = $interval->days > 120 ? "Eligible for donation" : "Not eligible for donation";
                // Display donor information in a styled box
                echo "<div style = 'background-color: #f9f9f9; 
                            text-align: center;
                            border: 2px solid #ccc; 
                            border-radius: 30px;
                            padding: 30px; 
                            margin: 20px auto;
                            margin-top: 20px;
                            line-height: 1.8;
                            width: 50%;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);' >"; // Start of info box

                echo "<h3>Your Information:</h3>";
                echo "Full Name: " . htmlspecialchars($row["fullName"]) . "<br>";

                echo "Mobile Number: " . htmlspecialchars($row["mobileNumber"]) . "<br>";
                echo "Email ID: " . htmlspecialchars($row["emailID"]) . "<br>";
                echo "Gender: " . htmlspecialchars($row["gender"]) . "<br>";
                echo "Age: " . htmlspecialchars($row["age"]) . "<br>";
                echo "Blood Group: " . htmlspecialchars($row["bloodGroup"]) . "<br>";

                echo "District: " . htmlspecialchars($row["district"]) . "<br>";
                echo "Last Donation Date: " . htmlspecialchars($row["lastDonationDate"]) . "<br>";
                echo "Eligibility: " . $isEligible . "<br>";
                echo "</div>"; // End of info box

                // Provide Update and Logout options

                // Show Update and Logout buttons after login
?>
                <!-- Display Update and Logout buttons -->
                <div class="button-container" style="text-align: center; margin-top: 20px;">

                    <form action="update.php" method="GET" style="display: inline-block; margin-right: 10px; text-align: center;">
                        <button type="submit" style="background-color: red; color: white; border: none; border-radius: 5px; text-decoration: none; padding: 10px 20px;" class="button">Update</button>
                    </form>

                    <form action="logout.php" method="POST" style="display: inline-block;">
                        <button type="submit" style="background-color: red; color: white; border: none; border-radius: 5px; text-decoration: none; padding: 10px 20px;" class="button">Logout</button>
                    </form>
                </div>

<?php


                exit; // Prevent further execution of the script after displaying info
            } else {
                // Invalid credentials
                $loginError = "Wrong email ID or password!";
            }
        } else {
            // No user found with that email ID
            $loginError = "No account found with that email ID.";
        }
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>

<body>
    <main class="background-image">
        <div class="item-center">
            <h2> Donor Login </h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="emailID">Email ID:</label>
                    <input type="email" placeholder="Enter email" id="emailID" name="emailID" required>
                    <span class="error"><?php echo $emailError; ?></span>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" placeholder="Enter password" id="password" name="password" required minlength="6">
                </div>

                <!-- Error message for wrong credentials -->
                <?php if (!empty($loginError)) {
                    echo "<p class='error'>$loginError</p>";
                } ?>

                <!-- Submit Button -->
                <button type="submit" class="button">Login</button>

            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>

<style>
    .background-image {
        background-image: url("image/pexels-artempodrez-6823567.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        height: 100vh;
        /* Full viewport height */
        display: flex;
        /* Enable Flexbox for centering */
        justify-content: center;
        /* Horizontally center the content */
        align-items: center;
        /* Vertically center the content */
    }

    .item-center {
        display: flex;
        /* Enable Flexbox for item alignment */
        flex-direction: column;
        /* Stack children vertically */
        justify-content: center;
        /* Vertically center within the element */
        align-items: center;
        /* Horizontally center within the element */
        text-align: center;
        /* Center text content */
        background-color: rgba(255, 255, 255, 0.9);
        /* Add a light background for better contrast */
        padding: 20px;
        /* Add padding around the content */
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Add shadow for a modern look */
        width: 90%;
        /* Set a relative width for smaller screens */
        max-width: 400px;
        /* Limit maximum width for larger screens */
    }

    input[type=email],
    input[type=password] {
        width: 90%;
        /* Adjust width relative to the form's size */
        max-width: 350px;
        /* Limit maximum width */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 10px 0;
        /* Add vertical spacing between fields */
    }

    button.button {
        width: 90%;
        /* Match the input width */
        max-width: 350px;
        /* Consistent button width */
        padding: 10px;
        font-size: 16px;
        /* Larger font size for better readability */
        margin-top: 10px;
        /* Add space above the button */
    }
</style>