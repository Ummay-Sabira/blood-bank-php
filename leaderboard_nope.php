<?php

include("database.php");

// 1. Update UserPoints based on lastDonationDate
$query = "
    INSERT INTO UserPoints (user_id, points)
    SELECT emailID, 
           IF(lastDonationDate IS NULL, 20, 15) AS points
    FROM BloodDonors
    ON DUPLICATE KEY UPDATE points = points + IF(lastDonationDate IS NULL, 20, 15);
";

// Execute the query
if (mysqli_query($conn, $query)) {
    echo "User points updated successfully!";
} else {
    echo "Error updating points: " . mysqli_error($conn);
}

// 2. Fetch users and their points for the leaderboard
$query = "
    SELECT BloodDonors.emailID, BloodDonors.name, UserPoints.points
    FROM BloodDonors
    JOIN UserPoints ON BloodDonors.emailID = UserPoints.emailID
    ORDER BY UserPoints.points DESC;
";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "<h2>Leaderboard</h2>";
    echo "<table><tr><th>Name</th><th>Email</th><th>Points</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['emailID']) . "</td><td>" . htmlspecialchars($row['points']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No leaderboard data found.";
}
?>
