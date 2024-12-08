<?php
include 'header.php';
include("database.php");

$output = ''; // Initialize output

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $bloodGroup = $_POST['bloodGroup'];
    $district = $_POST['district'];

    // Prepare the SQL query
    $search_donorqry = "SELECT fullName, mobileNumber, emailID, gender, age, district ,lastDonationDate
                        FROM BloodDonors 
                        WHERE bloodGroup = ? AND district = ?";

    $stmt = $conn->prepare($search_donorqry);
    $stmt->bind_param("ss", $bloodGroup, $district); // Bind parameters
    $stmt->execute();
    $result = $stmt->get_result(); // Execute the query and fetch results

    if ($result && $result->num_rows > 0) {
        // Loop through all matching donors
        while ($row = $result->fetch_assoc()) {
            // Calculate the number of days since the last donation
            $lastDonationDate = new DateTime($row['lastDonationDate']);
            $currentDate = new DateTime();
            $interval = $lastDonationDate->diff($currentDate);
            $daysSinceLastDonation = $interval->days;

            if (($row['gender'] == 'Male' && $daysSinceLastDonation >= 120) ||
                ($row['gender'] == 'Female' && $daysSinceLastDonation >= 180)
            ) {

                // Output eligible donor details
                $output .= '<div class="donor-info">';
                $output .= '<p><strong>Name:</strong> ' . htmlspecialchars($row['fullName']) . '</p>';
                $output .= '<p><strong>Mobile Number:</strong> ' . htmlspecialchars($row['mobileNumber']) . '</p>';
                $output .= '<p><strong>Email:</strong> ' . htmlspecialchars($row['emailID']) . '</p>';
                $output .= '<p><strong>Gender:</strong> ' . htmlspecialchars($row['gender']) . '</p>';
                $output .= '<p><strong>Age:</strong> ' . htmlspecialchars($row['age']) . '</p>';
                $output .= '<p><strong>District:</strong> ' . htmlspecialchars($row['district']) . '</p>';
                $output .= '<p><strong>Status:</strong> Eligible</p>';
                $output .= '</div><hr>';
            } else {
                // If no matching donors are found
                $output = '<p>No  eligible donor found for the selected blood group and district.</p>';
            }
        }
    } else {
        // If no matching donors are found
        $output = '<p>No donor found for the selected blood group and district.</p>';
    }

    $stmt->close();
}

$conn->close(); // Close the database connection
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Donor</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>

<body>
    <main class="background-image">
        <div class="item-center">
            <h2>Search for a Blood Donor</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="form-group">
                    <label for="bloodGroup">Select Blood Group:</label>
                    <select id="bloodGroup" name="bloodGroup" required>
                        <option value="">Select Blood Group</option>
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
                        <option value="">Select District</option>
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

                    </select>
                </div>

                <button type="submit" class="button">Search Donor</button>
            </form>
            <section>
                <?php echo $output; ?>
            </section>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>

<style>
    /* styles.css */
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
    /* Add this CSS to style the form */
    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
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
        padding: 40px;
        /* Add padding around the content */
        border-radius: 10px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Add shadow for a modern look */
        width: 100%;
        /* Set a relative width for smaller screens */
        max-width: 400px;
        /* Limit maximum width for larger screens */
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
        font-size: 16px;
        /* Font size */
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
        height: 100vh;
    }
</style>