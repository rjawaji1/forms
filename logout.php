<?php
session_start();
if(isset($_SESSION['username'])) {
    session_destroy();
    header('Location: index.php');
} else {
    $_SESSION['error'] = 'You are not logged in';
    header('Location: index.php');
}