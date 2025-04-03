<?php
include_once('../config/database.php');
include_once('../includes/util.inc.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$question_id = sanitize_input($_POST["questionId"]);
$form_id = sanitize_input($_POST["formId"]);

$stmt = $conn -> prepare("SELECT choices FROM questions WHERE id = ?");
$stmt -> bind_param('i', $question_id);
$stmt -> execute();
$stmt -> bind_result($choice_count);
$stmt -> fetch();
$stmt -> close();

$choice_count = (int)$choice_count + 1;
$choice_text = "Choice $choice_count";

$stmt = $conn -> prepare("INSERT INTO multiple_choice_choices(question_id, description, position) VALUES (?,?,?)");
$stmt -> bind_param('isi', $question_id, $choice_text, $choice_count); 
if($stmt -> execute()){
    $conn -> execute_query("UPDATE questions SET choices = choices + 1, updated_at = NOW() WHERE id = $question_id");
} else {
    $_SESSION["error"] = "failed to create a new choice";
}
$stmt -> close();

header("Location: ../form.php?id=$form_id");
exit(0);

