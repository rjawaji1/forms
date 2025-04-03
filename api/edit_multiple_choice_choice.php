<?php
include("../config/database.php");
include("../includes/util.inc.php");
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$choice_description = sanitize_input($_POST['choiceDescription']);
$action = $_POST["action"];

$choice_position = $_POST['choicePosition'];
$choice_id = $_POST["choiceId"];
$question_id = $_POST["questionId"];
$form_id = $_POST["formId"];

switch($action){
    case "delete":
        $conn -> begin_transaction();
        $conn -> execute_query("DELETE FROM multiple_choice_choices WHERE id = ?", [$choice_id]);
        $conn -> execute_query("UPDATE multiple_choice_choices SET position = position - 1 WHERE position > ?", [$choice_position]);
        $conn -> execute_query("UPDATE questions SET choices = choices - 1 WHERE id = ?", [$question_id]);
        $conn -> commit();
        break;
    case "moveUp":
        $new_choice_position = (int)$choice_position - 1;
        
        # Get id of the position to swap with
        $stmt = $conn -> prepare("SELECT id FROM multiple_choice_choices WHERE question_id = ? AND position = ?");
        $stmt -> bind_param("ii", $question_id, $new_choice_position);
        $stmt -> execute();
        $stmt -> bind_result($old_choice_id);
        if(!($stmt -> fetch())) {
            header("Location: ../form.php?id=$form_id");
            exit(0);
        }
        $stmt -> close();

        # Swap Positions
        $conn -> execute_query("UPDATE multiple_choice_choices SET position = position - 1, updated_at = NOW() WHERE id = $choice_id");
        $conn -> execute_query("UPDATE multiple_choice_choices SET position = position + 1, updated_at = NOW() WHERE id = $old_choice_id");
        break;
    case "moveDown":
        $new_choice_position = (int)$choice_position + 1;
        # Get id of the position to swap with
        $stmt = $conn -> prepare("SELECT id FROM multiple_choice_choices WHERE question_id = ? AND position = ?");
        $stmt -> bind_param("ii", $question_id, $new_choice_position);
        $stmt -> execute();
        $stmt -> bind_result($old_choice_id);
        if(!($stmt -> fetch())) {
            header("Location: ../form.php?id=$form_id");
            exit(0);
        }
        $stmt -> close();

        # Swap Positions
        $conn -> execute_query("UPDATE multiple_choice_choices SET position = position + 1, updated_at = NOW() WHERE id = $choice_id");
        $conn -> execute_query("UPDATE multiple_choice_choices SET position = position - 1, updated_at = NOW() WHERE id = $old_choice_id");
        break;
}

header("Location: ../form.php?id=$form_id");
exit(0);
