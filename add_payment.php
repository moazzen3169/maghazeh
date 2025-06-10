<?php
// Connect to database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "salam";

// Create connection
$conn = new mysqli($servername, username: $username, password: $password, database: $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$destination = $_POST['destination'];
$amount = $_POST['amount'];
$payment_date = $_POST['payment_date'];
$reason = $_POST['reason'];

// Insert into database
$sql = "INSERT INTO payments (destination, amount, payment_date, reason, created_at, updated_at)
        VALUES ('$destination', $amount, '$payment_date', '$reason', NOW(), NOW())";

if ($conn->query($sql) === TRUE) {
    // Redirect back with success message
    header("Location: pay.php?success=1");
} else {
    // Redirect back with error message
    header("Location: pay.php?error=1");
}

$conn->close();
?>