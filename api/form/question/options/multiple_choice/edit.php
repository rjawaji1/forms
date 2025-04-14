<?php
include_once("../../../../../config/database.php");

$choice_id = $_POST['choice_id'];
$question_id = $_POST['question_id'];
$action = $_POST['action'];

$conn -> begin_transaction();

try {
    switch($action) {
        case "move_up":
            $result = $conn -> execute_query(
                 "SELECT position FROM multiple_choice_choices WHERE id = ?",
                 [$choice_id]                   
            ) -> fetch_assoc();

            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position - 1 WHERE id = ?",
                [$choice_id]
            );

            $result = $conn -> execute_query(
                "SELECT id FROM multiple_choice_choices WHERE position = ? AND question_id = ?",
                [(int)$result["position"] - 1, $question_id]                
            ) -> fetch_assoc();

            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position + 1 WHERE id = ?",
                [(int)$result["id"]]
            );
            break;
        case "move_down":

            $result = $conn -> execute_query(
                 "SELECT position FROM multiple_choice_choices WHERE id = ?",
                 [$choice_id]                   
            ) -> fetch_assoc();

            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position + 1 WHERE id = ?",
                [$choice_id]
            );

            $result = $conn -> execute_query(
                "SELECT id FROM multiple_choice_choices WHERE position = ? AND question_id = ?",
                [(int)$result["position"] + 1, $question_id]                
            ) -> fetch_assoc();

            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET position = position - 1 WHERE id = ?",
                [(int)$result["id"]]
            );
            break;

        case "edit_options":
            $role = $_POST['role'];
            switch($role){
                case "multiple":
                    $conn -> execute_query(
                        "UPDATE multiple_choice_questions SET multiple = NOT multiple WHERE id = ?",
                        [$question_id]
                    );
                    break;
                case "long":
                    $conn -> execute_query(
                        "UPDATE text_questions SET long_answer = NOT long_answer WHERE id = ?",
                        [$question_id]
                    );
                    break;
                case "required":
                    $conn -> execute_query(
                        "UPDATE questions SET required = NOT required WHERE id = ?",
                        [$question_id]
                    );
                    break; 
            };
            break;
        case "update_choice_text":
            $new_value = $_POST["value"];
            $conn -> execute_query(
                "UPDATE multiple_choice_choices SET description = ? WHERE id = ?",
                [$new_value, $choice_id]
            );
            break;
            
    };

    $conn -> commit();
} catch(mysqli_sql_exception $e) {
    $conn -> rollback();
    header("HTTP/1.1 500 Database Error" . $e->getMessage());
    die();
} catch(Exception $e) {
    $conn -> rollback();
    header("HTTP/1.1 500 Unknown Error " . $e->getMessage());
    die();
}
?>
