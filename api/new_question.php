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

// Insert Question
$conn -> begin_transaction();
$conn -> execute_query("INSERT INTO questions (question, form_id, position, type) VALUES (?,?,?,?)", [$question_text, $question_form_id, $question_form_position, $question_type]);
$conn -> execute_query("UPDATE forms SET questions = questions + 1, updated_at = NOW()  WHERE id = ?", [$question_form_id]);
$conn -> commit();

// Redirect the user to the original page
header("Location: ../form.php?id=$question_form_id");
exit(0);
