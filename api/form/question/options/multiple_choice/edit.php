<?php
include_once("../../../../../config/database.php");

$choice_id = $_POST['choice_id'];
$question_id = $_POST['question_id'];
$action = $_POST['action'];

$conn -> begin_transaction();

try {
    switch($action) {
        case "move_up":
            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position - 1 WHERE id = ?",
                [$choice_id]
            );

            $result = $conn -> execute_query(
                 "SELECT position FROM multiple_choice_choices WHERE id = ?",
                 [$choice_id]                   
            ) -> fetch_assoc();

            $result = $conn -> execute_query(
                "SELECT id FROM multiple_choice_choices WHERE position = ? AND question_id = ?",
                [(int)$result["position"], $question_id]                
            ) -> fetch_assoc();

            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position + 1 WHERE id = ?",
                [(int)$result["id"]]
            );
            break;
        case "move_down":
            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position + 1 WHERE id = ?",
                [$choice_id]
            );

            $result = $conn -> execute_query(
                 "SELECT position FROM multiple_choice_choices WHERE id = ?",
                 [$choice_id]                   
            ) -> fetch_assoc();

            $result = $conn -> execute_query(
                "SELECT id FROM multiple_choice_choices WHERE position = ? AND question_id = ?",
                [(int)$result["position"], $question_id]                
            ) -> fetch_assoc();

            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position - 1 WHERE id = ?",
                [(int)$result["id"]]
            );
            break;
    }

    $conn -> commit();
} catch(mysqli_sql_exception $e){
    $conn -> rollback();
    http_response_code(500);
    die(); 
}
