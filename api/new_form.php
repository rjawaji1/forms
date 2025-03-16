<?php
include_once('config/database.php');
include_once('includes/util.inc.php');
session_start();

$form_name = sanitize_input($_POST['form_name']);
$form_user_id = $_SESSION['user_id'];

$conn = new mysqli($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DB);
$stmt = conn->prepare("INSERT INTO forms (name, user_id) VALUES (?, ?)");
$stmt->bind_param("si", $form_name, $form_user_id);

if($stmt->execute() != true){
    $_SESSION['error'] = 'Failed to create form';
}
