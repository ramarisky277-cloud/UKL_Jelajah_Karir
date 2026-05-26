<?php

$host = "localhost";
$username = "root";
$password = "rama";
$database = "db_ukl";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

