<?php
include_once("../../../../../includes/database.php");

$question_id = $_POST['question_id'];
$choice_id = $_POST['choice_id'];

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE multiple_choice_questions SET choices = choices - 1  WHERE id = ?",
        [$question_id]
    );

    $result = $conn -> execute_query(
        "SELECT position FROM multiple_choice_choices WHERE id = ?",
        [$choice_id]
    ) -> fetch_assoc();

    $position = $result["position"];

    $conn -> execute_query(
        "UPDATE multiple_choice_choices SET position = position - 1 WHERE question_id = ? AND position > ?",
        [$question_id, $position]  
    );

    $conn -> execute_query(
        "DELETE FROM multiple_choice_choices WHERE id = ?",
        [$choice_id]
    );

    $conn -> commit();
} catch(mysqli_sql_exception $e){
    $conn -> rollback();
    http_response_code(500);
    die(); 
}
