<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once('config/database.php');

// get database connection
$conn = new mysqli($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DB);

// Fetch the username and password from the POST request
$email = sanitize_input($_POST['email']);
$password = sanitize_input($_POST['password']);

// Query the database for the user
$query = "SELECT id, username FROM users WHERE email = '$email' AND password = SHA('$password')";
$result = $conn->query($query);

session_start();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $user_id = $row['id'];
    $_SESSION['user_id'] = $user_id;

    $username = $row['username'];
    $_SESSION['username'] = $username;
    
    header('Location: forms.php');
} else {
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: login.php');
}

function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}