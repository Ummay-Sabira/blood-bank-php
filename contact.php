<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome for icons -->
</head>
<body>
<main>
    <h2 class="center-title">Contact Us</h2>
    <div class="contact-info">
        <div class="contact-item">
            <i class="fas fa-envelope"></i>
            <div>
                <h3>Email</h3>
                <p>bloodbank@gmail.com</p>
            </div>
        </div>
        <div class="contact-item">
            <i class="fas fa-phone"></i>
            <div>
                <h3>Phone</h3>
                <p>+1 234 567 890</p>
            </div>
        </div>
        <div class="contact-item">
            <i class="fas fa-map-marker-alt"></i>
            <div>
                <h3>Address</h3>
                <p>45 Alankar Shopping Complex (1st Floor), Chattogram.</p>
            </div>
        </div>
    </div>
</main>
    <?php include 'footer.php'; ?>
</body>
</html>

<style>
/* styles.css */
/* Center the Contact Us heading */
.center-title {
    color: black; /* Adjust text color */
    font-size: 2.5em; /* Font size for visibility */
    text-align: center; /* Center align the text */
    background-color: rgba(0, 0, 0, 0.2); /* Optional: Add a translucent background for better contrast */
    padding: 15px 30px; /* Add padding around the text */
    border-radius: 10px; /* Rounded corners for the background */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: Add a shadow for emphasis */
}

/* Flex container to hold contact items */
.contact-info {
    display: flex;
    justify-content: center; /* Center the items horizontally */
    align-items: center; /* Center the items vertically */
    flex-wrap: wrap; /* Allow items to wrap to the next line on smaller screens */
    gap: 30px; /* Space between items */
    margin-top: 30px; /* Space above the contact items */
}

/* Style each contact item */
.contact-item {
    display: flex;
    flex-direction: column; /* Stack the icon and text vertically */
    align-items: center; /* Center items horizontally */
    text-align: center; /* Center the text */
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Light shadow */
    width: 200px; /* Set a fixed width */
}

.contact-item i {
    font-size: 2em; /* Adjust icon size */
    color: red; /* Icon color */
    margin-bottom: 10px; /* Space between icon and text */
}

.contact-item h3 {
    font-size: 1.2em;
    margin-bottom: 5px;
}

.contact-item p {
    font-size: 1em;
    color: #333;
}

/* Make sure it looks good on smaller screens */
@media (max-width: 768px) {
    .contact-item {
        width: 150px; /* Smaller width for mobile screens */
    }
}


</style>