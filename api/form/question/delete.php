<?php
include_once("../../../includes/database.php");

$question_id = $_POST['question_id'];
$form_id = $_POST['form_id'];

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE forms SET questions = questions - 1 WHERE id = ?",
        [$form_id]  
    );

    $result = $conn -> execute_query(
        "SELECT position FROM questions WHERE id = ?",
        [$question_id]
    ) -> fetch_assoc();

    $conn -> execute_query(
        "UPDATE questions SET position = position - 1 WHERE form_id = ? AND position > ?",
        [$form_id, $result["position"]]
    );

    $conn -> execute_query(
        "DELETE FROM questions WHERE id = ?",
        [$question_id]
    );

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
