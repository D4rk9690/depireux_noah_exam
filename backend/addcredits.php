<?php
// Assuming you have a database connection established
header("Content-Type: application/json");
$dbhost = "localhost";
$dbname = "noah_depireux_examen";
$dbuser = "root";
$dbpass = "";
$dbprefix = "noah"; // Replace "mon_prefixe" with your desired prefix

// Create a new MySQLi instance
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// Check the connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Retrieve the credits value from the POST request
$credits = $_POST['credits'];

// Assuming you have a session or user identifier to identify the user whose credits need to be updated
// Replace 'user_id' with your actual identifier or session variable
$userID = $_SESSION['user_id'];

// Update the user's credits in the database
$sql = "UPDATE " . $dbprefix . "_users SET credits = credits + $credits WHERE id = $userID";

if ($mysqli->query($sql) === TRUE) {
  $response = array(
      "status" => "success",
      "message" => "Credits added successfully",
      "totalCredits" => $credits // Assuming you want to return the updated total credits
  );
} else {
  $response = array(
      "status" => "error",
      "message" => "Error updating credits: " . $mysqli->error
  );
}

// Close the database connection
$mysqli->close();

// Set the response header as JSON
header("Content-Type: application/json");

// Send the JSON response
echo json_encode($response);
?>
