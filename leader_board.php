<?php
include("header.php");
// Include the database connection
include("database.php"); // Make sure this file connects to your MySQL database

// Function to calculate medals based on the last donation date
function getMedal($lastDonationDate) {
    $currentDate = date('Y-m-d');
    $diff = date_diff(date_create($lastDonationDate), date_create($currentDate));

    $totalDays = $diff->days; // Total difference in days
    $monthsDifference = $diff->m + ($diff->y * 12); // Calculate the difference in months

    // Convert months to approximate days for fine-tuned checks
    $approxDaysInMonth = 30; // Approximation
    $minDaysFor6Months = 6 * $approxDaysInMonth; // 180 days
    $minDaysFor7Months = 7 * $approxDaysInMonth; // 210 days

    // Determine medal based on the time difference
    if ($totalDays >= $minDaysFor6Months && $totalDays < $minDaysFor7Months) {
        return 'Silver'; // Between 6-7 months
    } elseif ($totalDays >= $minDaysFor7Months) {
        return 'Bronze'; // More than 7 months
    } elseif ($totalDays >= 4 * $approxDaysInMonth && $totalDays < $minDaysFor6Months) {
        return 'Gold'; // Between 4-6 months
    }
    return 'No Medal'; // No medal if the donation was too recent
}


// Query to fetch all donors and their last donation dates
$query = "SELECT fullName, lastDonationDate FROM BloodDonors ORDER BY lastDonationDate DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fullName, $lastDonationDate);

// Store donors in an array for later sorting by medal
$donors = [];

while (mysqli_stmt_fetch($stmt)) {
    // Assign medal based on the last donation date
    $medal = getMedal($lastDonationDate);
    $donors[] = ['fullName' => $fullName, 'lastDonationDate' => $lastDonationDate, 'medal' => $medal];
}

// Count medals
$medalCounts = ['Gold' => 0, 'Silver' => 0, 'Bronze' => 0];
foreach ($donors as $donor) {
    if (isset($medalCounts[$donor['medal']])) {
        $medalCounts[$donor['medal']]++;
    }
}

// Prepare top contributors array
$topContributors = [];
if ($medalCounts['Gold'] > 0) {
    $topContributors[] = ['medal' => 'Gold', 'count' => min(3, $medalCounts['Gold'])];
}
if ($medalCounts['Silver'] > 0) {
    $topContributors[] = ['medal' => 'Silver', 'count' => min(3 - count($topContributors), $medalCounts['Silver'])];
}
if ($medalCounts['Bronze'] > 0) {
    $topContributors[] = ['medal' => 'Bronze', 'count' => min(3 - count($topContributors), $medalCounts['Bronze'])];
}

// Collect top contributors data
$finalTopContributors = [];
foreach ($topContributors as $contributor) {
    foreach ($donors as $donor) {
        if ($donor['medal'] === $contributor['medal'] && count($finalTopContributors) < 3) {
            $finalTopContributors[] = ['fullName' => $donor['fullName'], 'medal' => $donor['medal']];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Blood Donation</title>
    <style>
        /* Inline CSS for red and white theme */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        nav {
            background-color: #e60000;
            padding: 10px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        main {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #e60000;
            font-size: 32px;
            margin-bottom: 10px;
        }

        p {
            text-align: center;
            color: white;
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Leaderboard table styling */
        .leaderboard-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .leaderboard-table th, .leaderboard-table td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .leaderboard-table th {
            background-color: #f2f2f2;
            font-size: 20px;
            color: #333;
        }

        .leaderboard-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .leaderboard-table tr:hover {
            background-color: #eaeaea;
        }

        .leaderboard-table td {
            font-size: 18px;
            color: #555;
        }

        

        /* Medals styling */
        td:nth-child(3) {
            font-weight: bold;
            color: #333;
        }
        
    </style>
</head>
<body>

    <!-- Main content area -->
    <main>
        <h2>Top Donors Leaderboard</h2>
        
        <table class="leaderboard-table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Medal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display top contributors in the leaderboard
                foreach ($finalTopContributors as $index => $contributor) {
                    echo "<tr>";
                    echo "<td>" . ($index + 1) . "</td>";
                    echo "<td>{$contributor['fullName']}</td>";
                    echo "<td>{$contributor['medal']}</td>";
                    echo "</tr>";
                }
                ?>
                <?php if (empty($finalTopContributors)): ?>
                    <tr><td colspan="3">No contributors available.</td></tr>
                <?php endif; ?>
                
            </tbody>
        </table>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>

<?php
// Close the database connection
mysqli_stmt_close($stmt);
?>