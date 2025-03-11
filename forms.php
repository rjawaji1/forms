<?php
    include_once('includes/partials/header.inc.php');
    include_once('config/database.php');

    $conn = new mysqli($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DB);
    
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name FROM forms WHERE user_id=$user_id";

    $result = $conn->query($query);
?>  

<main>
    <div class="container text-center">
        <div class="row row-cols-auto">
            <?php foreach($result as $row) :?>
                <a href="form.php?name=<?php echo $row['name'] ?>"><?php echo $row['name'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</main>


<?php
    include_once('includes/partials/footer.inc.php');
?>