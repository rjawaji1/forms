<?php
require_once('includes/partials/header.inc.php');
require_once('includes/util.inc.php');
require_once('includes/database.php');

if(!isset($_GET["id"])){
   header("Location: forms.php"); 
   exit(0);
}

$form_id = sanitize_input($_GET["id"]);

// Fetch Form
$stmt = $conn -> prepare("SELECT name FROM forms WHERE id = ?");
$stmt -> bind_param("i", $form_id);
$stmt -> execute();
$stmt -> bind_result($form_name);
if(!$stmt -> fetch()){
    $_SESSION["error"] = "Invalid Form";
    header("Location: forms.php");
    exit(0);
}
$stmt -> close();

// Fetch all the questions
$stmt = $conn -> prepare("SELECT id, question, position, type, required FROM questions WHERE form_id = ? ORDER BY position ASC");
$stmt -> bind_param("i", $form_id);
$stmt -> execute();
$stmt -> store_result();
$stmt -> bind_result($question_id, $question, $question_position, $question_type, $question_required);
?>

<main class="container">
    <div id="form-designer">
        <h1><?=$form_name?></h1>
        <?php while($stmt -> fetch()) :?>
            <div class="question" data-question-id="<?=$question_id?>" data-question-position="<?=$question_position?>">             
                <?php
                switch($question_type){
                    case "multiple_choice":
                        $result = $conn -> execute_query(
                            "SELECT multiple, max_choices FROM multiple_choice_questions WHERE id = ?",
                            [$question_id]
                        ) -> fetch_assoc();
                        break;
                    case "text":
                        $result = $conn -> execute_query(
                            "SELECT long_answer FROM text_questions WHERE id = ?",
                            [$question_id]  
                        ) -> fetch_assoc();
                        break;
                }
                ?>

                <div class="question-controls">
                    <button class="btn" data-action="q_delete"><i class="bi bi-x"></i></button>
                    <button class="btn" data-action="q_move_up"><i class="bi bi-arrow-up-short"></i></button>
                    <button class="btn" data-action="q_move_down"><i class="bi bi-arrow-down-short"></i></button>
                </div>

                <div class="question-header">
                    <input class="form-control" name="question" value="<?=$question?>">
                </div>

                <div class="question-body">
                    <?php switch($question_type): case "multiple_choice" :?>
                        <?php
                        $choice_stmt = $conn -> prepare("SELECT id, description, position FROM multiple_choice_choices WHERE question_id = ? ORDER BY position ASC");
                        $choice_stmt -> bind_param("i", $question_id);
                        $choice_stmt -> execute();
                        $choice_stmt -> bind_result($mco_id, $mco_text, $mco_pos);
                        $mco_type = $result["multiple"];

                        while($choice_stmt -> fetch()){
                            include("includes/components/mcq_component.php");
                        }
                        $choice_stmt -> close();
                        ?>
                    <?php break; case "text" :?>
                        <?php if($result["long_answer"]) :?>
                            <textarea class="form-control" disabled></textarea>
                        <?php else: ?>
                            <input class="form-control" type="text" disabled>
                        <?php endif; ?>
                    <?php endswitch; ?>
                </div>
                
                <div class="question-controls">
                    <?php switch($question_type): case "multiple_choice" :?>
                        <button class="btn me-auto" data-action="mcq_add_option">Add Option</button>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-multiple" <?=$result["multiple"] ? "checked" : "" ?>>
                            <label class="form-check-label" for="<?=$question_id?>-multiple">Multiple Answers</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-required" <?=$question_required ? "checked" : "" ?>>
                            <label class="form-check-label" for="<?=$question_id?>-required">Required</label>
                        </div>
                    <?php break; case "text" :?>
                        <div class="form-check form-switch ms-auto">
                            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-long" <?=$result["long_answer"] ? "checked" : "" ?>>
                            <label class="form-check-label" for="<?=$question_id?>-long">Long Answer</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="<?=$question_id?>-required" <?=$question_required ? "checked" : "" ?>>
                            <label class="form-check-label" for="<?=$question_id?>-required" >Required</label>
                        </div>
                    <?php endswitch; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div>
        <button id="create_multiple_choice" class="btn btn-primary">Multiple Choice</button>
        <button id="create_text" class="btn btn-primary">Text Answer</button>
    </div>
</main>

<?php
$stmt -> close();
require_once('includes/partials/footer.inc.php')
?>
