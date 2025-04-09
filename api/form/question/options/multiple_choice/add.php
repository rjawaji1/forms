<?php
include_once("../../../../../config/database.php");

$form_id = $_POST['form_id'];
$question_id = $_POST['question_id'];

$result = $conn -> execute_query(
    "SELECT multiple, choices FROM multiple_choice_questions WHERE id = ?",
    [$question_id]
) -> fetch_assoc();

$choice_count = (int)$result["choices"] + 1;
$choice_text = "Choice $choice_count";

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE multiple_choice_questions SET choices = choices + 1 WHERE id = ?",
        [$question_id]
    );

    $conn -> execute_query(
        "INSERT INTO multiple_choice_choices(question_id, description, position) VALUES (?,?,?)",
        [$question_id, $choice_text, $choice_count]
    );

    $choice_id = $conn -> insert_id;

    $conn -> commit();
    ?>

    <div data-choice-id="<?=$choice_id?>" data-choice-position="<?=$choice_count?>">
        <input type="<?=$result["multiple"] ? "checkbox" : "radio"?>" name="<?=$question_id?>" id="<?=$choice_id?>">
        <input value="<?=$choice_text?>">
        <div>
            <button data-action="mco_delete">x</button>
            <button data-action="mco_move_up">↑</button>
            <button data-action="mco_move_down">↓</button>
        </div>
    </div>

    <?php
} catch(mysqli_sql_exception $e){
    $conn -> rollback();
    http_response_code(500);
    die();
}
?>


