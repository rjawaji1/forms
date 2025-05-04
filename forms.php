<?php
include_once('includes/partials/header.inc.php');
include_once('config/database.php');
include_once('includes/util.inc.php');

    
$user_id = sanitize_input($_SESSION['user_id']);

$stmt = $conn-> prepare("SELECT id,name FROM forms WHERE user_id=?");
$stmt -> bind_param("i", $user_id);
$stmt -> execute();
$stmt -> bind_result($form_id, $form_name)
?>

<main>
    <div class="container text-center">
        <div class="row row-cols-auto">
            <?php while($stmt->fetch()) :?>
                <a href="form.php?id=<?=$form_id?>"><?=$form_name?></a>
            <?php endwhile; ?>
        </div>
    </div>
    <form action="api/form/create.php" method="POST">
        <label for="form_name">Form Name: </label>
        <input class="form-control" id="form_name" name="form_name">
        <input type="submit" value="Create Form">
    </form>
</main>


<?php
include_once('includes/partials/footer.inc.php');
?>
