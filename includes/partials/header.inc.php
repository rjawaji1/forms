<?php
    // Error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    // Start session
    session_start();
    $logged_in = isset($_SESSION['user']) != null;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Page Title</title>
</head>

<body>

<header>
    <?php if(isset($_SESSION['error'])) : ?>
        <p><?=$_SESSION['error']?></p>
        <?php unset($_SESSION['error']) ?>
    <?php endif; ?>

    <nav>
        <?php if($logged_in) : ?>
            <p>Welcome, <? $_SESSION['user'] ?></p>
        <?php else : ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>