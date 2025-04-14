<?php
include_once("../../../config/database.php");

$form_id = $_POST["form_id"];
$question_type = $_POST["type"];

$result = $conn -> execute_query(
    "SELECT questions FROM forms WHERE id = ?",
    [$form_id]
) -> fetch_assoc();


$question_position = (int)$result["questions"] + 1;
$question_text = "Question";

$conn -> begin_transaction();

try {
    $conn -> execute_query(
        "UPDATE forms SET questions = questions + 1 WHERE id = ?",
        [$form_id]
    );

    $conn -> execute_query(
        "INSERT INTO questions (form_id, question, position, type) VALUES (?,?,?,?)",
        [$form_id, $question_text, $question_position, $question_type]
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
            <input name="question" value="<?=$question_text?>">
        </div>

        <div class="question-body">
            <?php switch($question_type): case "multiple_choice" :?>
                <?php
                $mco_type = false;

                $mco_id = $choice_one_id;
                $mco_text = "Choice 1";
                $mco_pos = 1;
                include("../../../includes/components/multiple_choice_question_component.php");

                $mco_id = $choice_two_id;
                $mco_text = "Choice 2";
                $mco_pos = 2;
                include("../../../includes/components/multiple_choice_question_component.php");
                ?>
            <?php break; case "text" :?>
                <input type="text" disabled>
            <?php endswitch; ?>
        </div>

        <div class="question-controls">
            <?php switch($question_type): case "multiple_choice" :?>
                <button data-action="mcq_add_option">Add Option</button>
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
    header("HTTP/1.1 500 Database Error " . $e->getMessage());
    die();
} catch(Exception $e) {
    $conn -> rollback();
    header("HTTP/1.1 500 Unknown Error " . $e->getMessage());
    die();
}
?>

