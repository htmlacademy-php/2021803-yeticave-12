<?php
session_start();
$db = require_once 'db.php';
$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");
if (!$link) {
    print("Error MySQL: " . mysqli_connect_error());
    die();
}