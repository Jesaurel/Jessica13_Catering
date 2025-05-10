<?php
$servername = "localhost"; // Or your database host
$username = "root"; // Your database username
$password = "Jess1_MySql!"; // Your database password
$dbname = "jessicaaurelclarista_13_catering"; // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>