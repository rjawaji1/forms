<?php
session_start();

include_once("../../includes/database.php");

$user_id = $_SESSION["user_id"];
$form_name = $_POST["form_name"];

$conn -> execute_query(
    "INSERT INTO forms (user_id, name) VALUES (?,?)",
    [$user_id, $form_name]
);

$form_id = $conn -> insert_id;

header("Location: ../../form.php?id=" . $form_id);


