<?php
session_start(); // Start session management
include("database.php");
include("header.php");

// Initialize variable for error message
$loginError = '';

// Check if the form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the entered username and password from the form
    $userName = $_POST['userName'];
    $password = $_POST['password'];

    // Validate if the username and password fields are not empty
    if (empty($userName) || empty($password)) {
        $loginError = "Username and password are required.";
    } else {
        // Sanitize user input to prevent SQL injection
        $userName = mysqli_real_escape_string($conn, $userName);
        $password = mysqli_real_escape_string($conn, $password);

        // Query the database to check if the username matches
        $query = "SELECT * FROM Admin WHERE userName = '$userName' LIMIT 1";
        $result = mysqli_query($conn, $query);

        // Check if a matching record was found
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify the password (assuming it's not hashed)
            if ($password === $row['password']) {
                // Login successful, set session variables
                $_SESSION['user_id'] = $row['adminID'];
                $_SESSION['user_role'] = 'admin';

                // Redirect to admin dashboard (the same page)
                header('Location: admin.php');
                exit;
            } else {
                // Invalid credentials
                $loginError = "Wrong username or password!";
            }
        } else {
            // No user found with that username
            $loginError = "No account found with that username.";
        }
    }
}

// Check if the user is logged in as an admin for managing donors
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    // Initialize variable for error message for deletion
    $deleteError = '';

    // Check if a delete request has been made
    if (isset($_GET['delete'])) {
        $donorID = $_GET['delete'];

        // Sanitize the donor ID to prevent SQL injection
        $donorID = mysqli_real_escape_string($conn, $donorID);

        // Delete query
        $deleteQuery = "DELETE FROM BloodDonors WHERE donorID = '$donorID'";

        try  {
            mysqli_query($conn,$deleteQuery);
            
           echo "<p>Donor info deleted successfully!</p>";
            // Redirect to index.html after 2 seconds
            header("Refresh: 2; url=admin.php"); 
            exit(); // Stop further execution after redirection
            
       
          
         } 
         catch(mysqli_sql_exception $e) {
            echo "re-enter data ".$e ;
         }

    }

    // Fetch all donors from the database
    $query = "SELECT * FROM BloodDonors";
    $result = mysqli_query($conn, $query);

    // Query to count donors by district
    $districtQuery = "SELECT district, COUNT(*) as donor_count FROM BloodDonors GROUP BY district";
    $districtResult = mysqli_query($conn, $districtQuery);

    $districts = [];
    $donorCounts = [];

    while ($row = mysqli_fetch_assoc($districtResult)) {
        $districts[] = $row['district'];
        $donorCounts[] = (int)$row['donor_count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>

<body>
    <main>
        <?php if (!isset($_SESSION['user_role'])) { ?>
            <h2>Admin Login</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="userName">Username:</label>
                    <input type="text" id="userName" name="userName" required>
                </div>



                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>

                <!-- Error message -->
                <?php if (!empty($loginError)) {
                    echo "<p class='error'>$loginError</p>";
                } ?>

                <!-- Submit Button -->
                <button type="submit" class="button">Login</button>
            </form>
        <?php } else { ?>
            <h2>All Donors</h2>

            <?php if (!empty($deleteError)) {
                echo "<p class='error'>$deleteError</p>";
            } ?>

            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Mobile Number</th>
                        <th>Email ID</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>District</th>
                        <th>Last Donation Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['fullName']); ?></td>
                            <td><?php echo htmlspecialchars($row['mobileNumber']); ?></td>
                            <td><?php echo htmlspecialchars($row['emailID']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['age']); ?></td>
                            <td><?php echo htmlspecialchars($row['district']); ?></td>
                            <td><?php echo htmlspecialchars($row['lastDonationDate']); ?></td>

                            <td><a href="?delete=<?php echo $row['donorID']; ?>" class="button">Delete</a></td> <!-- Delete button -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Canvas for Chart -->
            <canvas id="donorChart" style="height: 400px; width: 600px;"></canvas>

            <script>
                // Render Donor Chart
                const ctx = document.getElementById('donorChart').getContext('2d');
                const donorChart = new Chart(ctx, {
                    type: 'bar', // Choose 'bar', 'line', etc.
                    data: {
                        labels: <?php echo json_encode($districts); ?>,
                        datasets: [{
                            barPercentage: 1,
                            barThickness: 40,
                            categoryPercentage: 0.8, // Overall category width

                            minBarLength: 0.5,
                            label: 'Number of Donors',
                            data: <?php echo json_encode($donorCounts); ?>,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 10, // Compress Y-axis range; adjust this value based on your data
                ticks: {
                    stepSize: 2 // Reduce step size to compress further
                }
                            }
                        }
                    }
                });
            </script>
            <!-- Logout Button -->
            <form action="adminLogout.php" method="POST" style="margin-top: 40px; text-align: right;">
                <button type="submit" class="button">Logout</button> <!-- Logout button -->
            </form>
        <?php } ?>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>

<style>
    /* styles.css */
    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type=text],
    input[type=password] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Add this CSS to style the table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid #ccc;
    }

    th,
    td {
        padding: 10px;
    }

    th {
        background-color: #f2f2f2;
        /* Light gray background for header */
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
        text-decoration: none;
        /* Remove underline */
    }

    .button:hover {
        background-color: darkred;
        /* Darker shade on hover */
    }

    .error {
        color: red;
        /* Error message color */
    }
</style>