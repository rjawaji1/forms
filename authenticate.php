<?php
include_once('config/database.php');
include_once('includes/util.inc.php');

// Fetch the username and password from the POST request
$email = sanitize_input($_POST['email']);
$password = sanitize_input($_POST['password']);

// Query the database for the user
$stmt = $conn-> prepare("SELECT id, username FROM users WHERE email = ? AND password = SHA(?)");
$stmt -> bind_param("ss", $email, $password);
$stmt -> execute();
$stmt -> bind_result($user_id, $username);

session_start();
if ($stmt -> fetch()) {
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;    
    header('Location: forms.php');
} else {
    $_SESSION['error'] = 'Invalid username or password';
    header('Location: login.php');
}
