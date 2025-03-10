<?php
    include_once('includes/partials/header.inc.php')
?>

<main>
    <form action="authenticate.php" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>
        <button type="submit">Login</button>
    </form>
</main>

<?php
    include_once('includes/partials/footer.inc.php')
?>