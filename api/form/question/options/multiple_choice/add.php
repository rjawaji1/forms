<?php
include_once("../../../../../config/database.php");

$form_id = $_POST['form_id'];
$question_id = $_POST['question_id'];

$stmt = $conn -> prepare("SELECT choices FROM questions WHERE id = ?");
$stmt -> bind_param('i', $question_id);
$stmt -> execute();
$stmt -> bind_result($choice_count);
$stmt -> fetch();
$stmt -> close();

$choice_count = (int)$choice_count + 1;
$choice = "Choice $choice_count";

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE questions SET choices = choices + 1, updated_at = NOW() WHERE id = ?",
        [$question_id]
    );

    $conn -> execute_query(
        "INSERT INTO multiple_choice_choices(question_id, description, position) VALUES (?,?,?)",
        [$question_id, $choice_text, $choice_count]
    );

    $conn -> commit();
    ?>

    <div data-choice-id="<?=$choice_id?>">
        <input type="radio" name="<?=$question_id?>">
        <input value="<?=$choice_text?>">
        <div>
            <button data-action="delete">x</button>
            <button data-action="move_up">↑</button>
            <button data-action="move_down">↓</button>
        </div>
    </div>

    <?php
} catch(){
    $conn -> rollback();
    http_response_code(500);
    }
?>


