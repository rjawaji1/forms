<?php
include_once("../../../../../includes/database.php");

$form_id = $_POST['form_id'];
$question_id = $_POST['question_id'];

$result = $conn -> execute_query(
    "SELECT multiple, choices FROM multiple_choice_questions WHERE id = ?",
    [$question_id]
) -> fetch_assoc();

$mco_pos = (int)$result["choices"] + 1;
$mco_text = "Choice $mco_pos";
$mco_type = $result["multiple"];

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE multiple_choice_questions SET choices = choices + 1 WHERE id = ?",
        [$question_id]
    );

    $conn -> execute_query(
        "INSERT INTO multiple_choice_choices(question_id, description, position) VALUES (?,?,?)",
        [$question_id, $mco_text, $mco_pos]
    );

    $mco_id = $conn -> insert_id;

    $conn -> commit();

    include("../../../../../includes/components/mcq_component.php");
} catch(mysqli_sql_exception $e){
    $conn -> rollback();
    
    header("HTTP/1.1 500 Database Error " . $e->getMessage());
    die();
} catch(Exception $e) {
    $conn -> rollback();
    header("HTTP/1.1 500 Unknown Error " . $e->getMessage());
    die();
}
?>


