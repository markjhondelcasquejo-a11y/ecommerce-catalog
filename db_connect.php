<?php
$host = "sql300.infinityfree.com";
$username = "if0_43436854";
$password = "CpHE8kEKivS";
$database = "if0_43436854_ecommerce_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>