<?php

/**
 * DATABASE.PHP
 * This file is run with every server ping.
 * Starts session and establishes connection with database.
 */

session_start();

$servername = "localhost";
$username = "paypartner";
$password = "E5oNd4dGXtPfEy7kArox";
$dbname = "PayPartner";

try {
  $conn = mysqli_connect($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $exception) {
  header("Location: ./dev/build.php");
  exit();
}