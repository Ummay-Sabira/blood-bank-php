<?php

include('database.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Management System</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>

<body>
    <header>
        <h1>Blood Donation Management System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li><a href="search_donor.php">Search Donor</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="admin.php">Admin</a></li>
                <li><a href="leader_board.php">Leader Board</a></li>


            </ul>
        </nav>
    </header>

    <main class="background-image">
    
        
        <section id="welcome">
            <h1>Welcome to the Blood Donation Management System</h1>
            <h3>Your contribution can save lives. Join us in making a difference!</h3>
        </section>
        <br>
        <!-- New Buttons Section -->
        <section id="buttons">
            <a href="register.php" class="button">Join as Donor</a>
            <a href="search_donor.php" class="button">Search Donor</a>
        </section>

    </main>

    <footer>
        <p>&copy; 2024 Blood Donation Management System. All rights reserved.</p>
    </footer>
</body>

</html>

<style>
    /* styles.css */

    /* Add this CSS to style the buttons */
    .button {
        display: inline-block;
        background-color: red;
        /* Button color */
        color: white;
        /* Text color */
        padding: 10px 20px;
        /* Padding around text */
        margin: 10px;
        /* Space between buttons */
        text-decoration: none;
        /* Remove underline */
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
    display: flex;
    flex-direction: column;
    justify-content: center;  /* Vertically centers content */
    align-items: center;      /* Horizontally centers content */
    height: 100vh;            /* Full viewport height */
    background-image: url("image/pexels-artempodrez-6823567.jpg"); /* Background image */
    background-size: cover;   /* Ensures the background image covers the entire area */
    background-position: center; /* Center the background image */
    text-align: center;       /* Centers text inside each section */
}
</style>