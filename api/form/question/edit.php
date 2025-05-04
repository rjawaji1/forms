<?php
include_once("../../../includes/database.php");

$form_id = $_POST['form_id'];
$question_id = $_POST['question_id'];
$action = $_POST['action'];

$conn -> begin_transaction();

try {
    switch ($action) {
        case "move_up":
            $result = $conn -> execute_query(
                "SELECT position FROM questions WHERE id = ?",
                [$question_id]
            ) -> fetch_assoc();

            $result = $conn -> execute_query(
                "SELECT id FROM questions WHERE position = ? AND form_id = ?",
                [(int)$result["position"] - 1, $form_id]
            ) -> fetch_assoc();
            
            $conn -> execute_query(
                "UPDATE questions SET position = position - 1 WHERE id = ?",
                [$question_id]
            );

            $conn -> execute_query(
                "UPDATE questions SET position = position + 1 WHERE id = ?",
                [$result["id"]]
            );
            break;
        case "move_down":
            $result = $conn -> execute_query(
                "SELECT position FROM questions WHERE id = ?",
                [$question_id]
            ) -> fetch_assoc();

            $result = $conn -> execute_query(
                "SELECT id FROM questions WHERE position = ? AND form_id = ?",
                [(int)$result["position"] + 1, $form_id]
            ) -> fetch_assoc();
            
            $conn -> execute_query(
                "UPDATE questions SET position = position + 1 WHERE id = ?",
                [$question_id]
            );

            $conn -> execute_query(
                "UPDATE questions SET position = position - 1 WHERE id = ?",
                [$result["id"]]
            );
            break;
        case "update_header":
            $new_value = $_POST["value"];
            $conn -> execute_query(
                "UPDATE questions SET question = ? WHERE id = ?",
                [$new_value, $question_id]
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
