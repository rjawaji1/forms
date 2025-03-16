<?php
require_once('includes/partials/header.inc.php');
require_once('includes/util.inc.php');
require_once('config/database.php');

$conn -> autocommit(false);

if(!isset($_GET["id"])){
   header("Location: forms.php"); 
   exit(0);
}

$form_id = sanitize_input($_GET["id"]);

// Fetch Form
$stmt = $conn -> prepare("SELECT 1 FROM forms WHERE id = ?");
$stmt -> bind_param("i", $form_id);
$stmt -> execute();
if(!$stmt -> fetch()){
    $_SESSION["error"] = "Invalid Form";
    header("Location: forms.php");
    exit(0);
}
$stmt -> close();

// Fetch all the questions
$stmt = $conn -> prepare("SELECT id, question, position, type FROM questions WHERE form_id = ? ORDER BY position ASC");
$stmt -> bind_param("i", $form_id);
$stmt -> execute();
$stmt -> store_result();
$stmt -> bind_result($question_id, $question, $postition, $type);
?>

<main>
    <div id="form-designer">
        <?php while($stmt -> fetch()) :?>
            <form action="api/edit_question.php" method="post">
                <input type="text" name="question" id="question" value="<?=$question?>">
                <!-- Choices -->
                <?php switch($type): case "multiple_choice" :?>
                    <?php
                    $choice_stmt = $conn -> prepare("SELECT id, description, position FROM multiple_choice_choices WHERE question_id = ? ORDER BY position ASC");
                    $choice_stmt -> bind_param("i", $question_id);
                    $choice_stmt -> execute();
                    $choice_stmt -> bind_result($choice_id, $choice_description, $choice_position);
                    ?>
                    <?php while($choice_stmt -> fetch()) :?>
                        <div><?=$choice_description?></div>
                    <?php endwhile; ?>
                <?php endswitch; ?>
                <input type="hidden" name="position" id="position" values="<?=$position?>">
                <input type="hidden" name="questionId" id="questionId" value="<?=$question_id?>">
            </form>
        <?php endwhile; ?>
    </div>
    <form action="api/new_question.php" method="post">
        <button name="type" value="multiple_choice">Multiple Choice</button>
        <input type="hidden" id="formId" name="formId" value="<?=$_GET["id"]?>">
    </form>
</main>

<?php
require_once('includes/partials/footer.inc.php')
?>
