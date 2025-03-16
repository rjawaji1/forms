<?php
include_once('../config/database.php');
include_once('../includes/util.inc.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$question_type = sanitize_input($_POST["type"]);
$question_form_id = sanitize_input($_POST["formId"]);

// Fetch the question
$stmt = $conn -> prepare("SELECT questions FROM forms WHERE id = ?");
$stmt -> bind_param("i", $question_form_id);
$stmt -> execute();
$stmt -> bind_result($question_form_position);
$stmt -> fetch();
$stmt -> close();

$question_form_position = (int)$question_form_position + 1;
$question_text = "Question $question_form_position";


// Insert questions
$stmt = $conn -> prepare("INSERT INTO questions (question, form_id, position, type) VALUES (?,?,?,?)");
$stmt -> bind_param("siis", $question_text, $question_form_id, $question_form_position, $question_type);
if($stmt -> execute()){
   $conn -> execute_query("UPDATE forms SET questions = questions + 1 WHERE id = $question_form_id");
}

// Close the statement
$stmt -> close();


// Redirect the user to the original page
header("Location: ../form.php?id=$question_form_id");
exit(0);
