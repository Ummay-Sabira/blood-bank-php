<?php
include 'header.php';
include 'functions.php';
include("database.php");
$emailError = '';
$mobileError = '';
$passwordError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $mobileNumber = $_POST['mobileNumber'];
    $emailID = $_POST['emailID'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $bloodGroup = $_POST['bloodGroupID'];
    $district = $_POST['district'];
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];
    $lastDonationDate = $_POST['lastDonationDate'];

    if (!filter_var($emailID, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email format";
    }

    if (!preg_match('/^\d{11}$/', $mobileNumber)) {
        $mobileError = "Mobile number must be 11 digits";
    }

    if ($password != $passwordConfirm) {
        $passwordError = "Passwords do not match";
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); /*
        
        // Prepare and bind SQL statement
        $sql ="INSERT INTO BloodDonors (fullName, mobileNumber, emailID, gender, age, bloodGroup, district, password, lastDonationDate) 
        VALUES ('$fullName', '$mobileNumber', '$emailID', '$gender', '$age', '$bloodGroup', '$district', '$hashedPassword', '$lastDonationDate')";
         
        try  {
             mysqli_query($conn,$sql);
             
            echo "<p>Registration successful!</p>";
             // Redirect to index.html after 2 seconds
             header("Refresh: 2; url=index.php"); 
             exit(); // Stop further execution after redirection
             
        
           
        } 
        catch(mysqli_sql_exception $e) {
            echo "re-enter data ".$e ;
        } */

    // Check if the email already exists in the database
    $checkEmailQuery = "SELECT * FROM BloodDonors WHERE emailID = '$emailID'";
    $result = mysqli_query($conn, $checkEmailQuery);

    //var_dump(mysqli_num_rows($result));

    if (mysqli_num_rows($result) > 0) {
        // Email already exists
        $generalError = "This email address is already registered. Please use a different email.";
        echo $generalError;
    } else {
        // Prepare and bind SQL statement
        $sql = "INSERT INTO BloodDonors (fullName, mobileNumber, emailID, gender, age, bloodGroup, district, password, lastDonationDate) 
                VALUES ('$fullName', '$mobileNumber', '$emailID', '$gender', '$age', '$bloodGroup', '$district', '$hashedPassword', '$lastDonationDate')";

        try {
            mysqli_query($conn, $sql);
            echo "<p>Registration successful!</p>";
            // Redirect to index.html after 2 seconds
            header("Refresh: 2; url=index.php");
            exit(); // Stop further execution after redirection
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>

<body>
    <main class="background-image">
        <div class="item-center">
            <h2>Please fill the form to register</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="fullName">Full Name:</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>

                <div class="form-group">
                    <label for="mobileNumber">Mobile Number:</label>
                    <input type="text" id="mobileNumber" name="mobileNumber" required>
                    <span class="error"><?php echo $mobileError; ?></span>
                </div>

                <div class="form-group">
                    <label for="emailID">Email ID:</label>
                    <input type="email" id="emailID" name="emailID" required>
                    <span class="error"><?php echo $emailError; ?></span>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" required min="1">
                </div>

                <div class="form-group">
                    <label for="bloodGroupID">Select Blood Group:</label>
                    <select id="bloodGroupID" name="bloodGroupID" required>
                        <option value="">Blood Group</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="district">Select District:</label>
                    <select id="district" name="district" required>
                        <option value="">District</option>
                        <!-- List of districts in Bangladesh -->
                        <option value="Dhaka">Dhaka</option>
                        <option value="Faridpur">Faridpur</option>
                        <option value="Gazipur">Gazipur</option>
                        <option value="Gopalganj">Gopalganj</option>
                        <option value="Jamalpur">Jamalpur</option>
                        <option value="Kishoreganj">Kishoreganj</option>
                        <option value="Madaripur">Madaripur</option>
                        <option value="Manikganj">Manikganj</option>
                        <option value="Munshiganj">Munshiganj</option>
                        <option value="Mymensingh">Mymensingh</option>
                        <option value="Narayanganj">Narayanganj</option>
                        <option value="Narsingdi">Narsingdi</option>
                        <option value="Netrokona">Netrokona</option>
                        <option value="Rajbari">Rajbari</option>
                        <option value="Shariatpur">Shariatpur</option>
                        <option value="Sherpur">Sherpur</option>
                        <option value="Tangail">Tangail</option>
                        <option value="Bogra">Bogra</option>
                        <option value="Joypurhat">Joypurhat</option>
                        <option value="Naogaon">Naogaon</option>
                        <option value="Natore">Natore</option>
                        <option value="Nawabganj">Nawabganj</option>
                        <option value="Pabna">Pabna</option>
                        <option value="Rajshahi">Rajshahi</option>
                        <option value="Sirajgonj">Sirajgonj</option>
                        <option value="Dinajpur">Dinajpur</option>
                        <option value="Gaibandha">Gaibandha</option>
                        <option value="Kurigram">Kurigram</option>
                        <option value="Lalmonirhat">Lalmonirhat</option>
                        <option value="Nilphamari">Nilphamari</option>
                        <option value="Panchagarh">Panchagarh</option>
                        <option value="Rangpur">Rangpur</option>
                        <option value="Thakurgaon">Thakurgaon</option>
                        <option value="Barguna">Barguna</option>
                        <option value="Barisal">Barisal</option>
                        <option value="Bhola">Bhola</option>
                        <option value="Jhalokati">Jhalokati</option>
                        <option value="Patuakhali">Patuakhali</option>
                        <option value="Pirojpur">Pirojpur</option>
                        <option value="Bandarban">Bandarban</option>
                        <option value="Brahmanbaria">Brahmanbaria</option>
                        <option value="Chandpur">Chandpur</option>
                        <option value="Chittagong">Chittagong</option>
                        <option value="Comilla">Comilla</option>
                        <option value="Cox's Bazar">Cox's Bazar</option>
                        <option value="Feni">Feni</option>
                        <option value="Khagrachari">Khagrachari</option>
                        <option value="Lakshmipur">Lakshmipur</option>
                        <option value="Noakhali">Noakhali</option>
                        <option value="Rangamati">Rangamati</option>
                        <option value="Habiganj">Habiganj</option>
                        <option value="Maulvibazar">Maulvibazar</option>
                        <option value="Sunamganj">Sunamganj</option>
                        <option value="Sylhet">Sylhet</option>
                        <option value="Bagerhat">Bagerhat</option>
                        <option value="Chuadanga">Chuadanga</option>
                        <option value="Jessore">Jessore</option>
                        <option value="Jhenaidah">Jhenaidah</option>
                        <option value="Khulna">Khulna</option>
                        <option value="Kushtia">Kushtia</option>
                        <option value="Magura">Magura</option>
                        <option value="Meherpur">Meherpur</option>
                        <option value="Narail">Narail</option>
                        <option value="Satkhira">Satkhira</option>
                        <!-- Add more districts as needed -->
                    </select>
                </div>

                <div class="form-group">
                    <label for="lastDonationDate">Last Donation Date:</label>
                    <input type="date" id="lastDonationDate" name="lastDonationDate">
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label for="passwordConfirm">Confirm Password:</label>
                    <input type="password" id="passwordConfirm" name="passwordConfirm" required minlength="6">
                    <span class="error"><?php echo $passwordError; ?></span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="button">Submit</button>
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
    input[type=number],
    select {
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
    .background-image {
        background-image: url("image/pexels-artempodrez-6823567.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        height: 110vh;
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
    /* Vertically center within the element */
    align-items: center;
    /* Horizontally center within the element */
    text-align: center;
    /* Center text content */
    background-color: rgba(255, 255, 255, 0.9);
    /* Add a light background for better contrast */
    padding: 3px 9px; /* Reduce padding to lessen form height */
    /* Add padding around the content */
    border-radius: 10px;
    /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    /* Add shadow for a modern look */
    width: 100%;
    /* Set a relative width for smaller screens */
    max-width: 600px; /* Increase max-width for wider form */
    min-width: 380px; /* Ensure form doesn't get too small */
    height: auto; /* Let the height grow based on content */
}
    
</style>