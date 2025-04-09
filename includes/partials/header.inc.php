<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Start session
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Title</title>
</head>

<body>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Forms</a>
        </div>

        <?php if(isset($_SESSION['username'])) : ?>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <?=$_SESSION['username']?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php else : ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>
