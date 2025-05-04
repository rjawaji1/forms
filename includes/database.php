<?php
$MYSQL_SERVER = 'localhost';
$MYSQL_USER = 'root';
$MYSQL_PASSWORD = '';
$MYSQL_DB = 'forms';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DB);
