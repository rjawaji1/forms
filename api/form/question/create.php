<?php
include_once("../../../config/database.php");

$form_id = $_POST["form_id"];
$question_type = $_POST["type"];

$stmt = $conn -> prepare("SELECT questions FROM forms WHERE id = ?");
$stmt -> bind_param("i", $form_id);
$stmt -> execute();
$stmt -> bind_result($question_count);
$stmt -> fetch();
$stmt -> close();

$question_position = (int)$question_count + 1;
$question = "Question $question_position";

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE forms SET questions = questions + 1 WHERE id = ?",
        [$form_id]
    );

    $conn -> execute_query(
        "INSERT INTO questions (form_id, question, position, type) VALUES (?,?,?,?)",
        [$form_id, $question, $question_position, $question_type]
    );

    $question_id = $conn -> insert_id;
    switch ($question_type){
        case "multiple_choice":
            $conn -> execute_query(
                "INSERT INTO multiple_choice_questions (id) VALUES (?)",
                [$question_id]
            );

            $conn -> execute_query(
                "INSERT INTO multiple_choice_choices (question_id, description, position) VALUES (?, 'choice 1', 1)",
                [$question_id]
            );

            $choice_one_id = $conn -> insert_id;

            $conn -> execute_query(
                "INSERT INTO multiple_choice_choices (question_id, description, position) VALUES (?, 'choice 2', 2)",
                [$question_id]
            );

            $choice_two_id = $conn -> insert_id;

            break;
        case "text":
            $conn -> execute_query(
                "INSERT INTO text_questions (id) VALUES (?)",
                [$question_id]
            );
            break;
    }

    $question_id = $conn -> insert_id;

    $conn -> commit();
    ?>

    <div class="question" data-question-id="<?=$question_id?>" data-question-position="<?=$question_position?>">
        <div class="question-header">
            <input name="question" value="<?=$question?>">
        </div>

        <div class="question-body">
            <?php switch($question_type): case "multiple_choice" :?>
                <div data-choice-id="<?=$choice_one_id?>">
                    <input type="radio" name="<?=$question_id?>" id="<?=$choice_one_id?>" >
                    <label for="<?=$choice_one_id?>">Choice 1</label>
                    <div>
                        <button data-action="delete">⨯</button>
                        <button data-action="move_up">↑</button>
                        <button data-action="move_down">↓</button>
                    <div>
                </div>
                <div data-choice-id="<?=$choice_two_id?>">
                    <input type="radio" name="<?=$question_id?>" id="<?=$choice_two_id?>" >
                    <label for="<?=$choice_two_id?>">Choice 2</label>
                    <div>
                        <button data-action="delete">⨯</button>
                        <button data-action="move_up">↑</button>
                        <button data-action="move_down">↓</button>
                    <div>
                </div>
            <?php break; case "text" :?>
                <input type="text" disabled>
            <?php endswitch; ?>
        </div>

        <div class="question-controls">
            <?php switch($question_type): case "multiple_choice" :?>
                <button data-action="add_option">Add Option</button>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-multiple">
                    <label class="form-check-label" for="<?=$question_id?>-multiple">Multiple Answers</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-required">
                    <label class="form-check-label" for="<?=$question_id?>-required">Required</label>
                </div>
            <?php break; case "text" :?>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-long">
                    <label class="form-check-label" for="<?=$question_id?>-long">Long Answer</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-required">
                    <label class="form-check-label" for="<?=$question_id?>-required">Required</label>
                </div>
            <?php endswitch; ?>
        </div>
    </div>    

    <?php
} catch(mysqli_sql_exception $exception){
    $conn -> rollback();
    http_response_code(500);
}
?>

