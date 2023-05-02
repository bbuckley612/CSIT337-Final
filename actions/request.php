<?php

/**
 * REQUEST.PHP
 * This file requires POST data. It handles all invoice creations.
 * - Request data is sanitized and validated
 * - xxx
 * - On success, xxx
 */


/* HANDLE POST */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Data sanitization
	$user_id = $_SESSION['user_id'];
  $recipient = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
	$description = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);

	// Data validation
	if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
    if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) { // TO-DO!!!!

      // Try to execute SQL statement
      try {
        $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$recipient' LIMIT 1");
        $recipient_id = mysqli_fetch_row($result)[0];
        mysqli_query($conn, "INSERT INTO `invoices` (`user_id`, `recipient_id`, `amount`, `description`, `status`) VALUES ($user_id, $recipient_id, '$amount', '$description', 1)");

        // New account is authorized! Let's call the cookie function
        header('Location: ./?view=main&try=request&success=1');
      } 

      // MySQL error; most likely recipient not found
      catch (mysqli_sql_exception $exception) {
        header('Location: ./?view=main&try=request&error=3');
      }

    // Invalid request amount
    } else {
      header('Location: ./?view=main&try=request&error=2');
    }

  // Invalid email address format
  } else {
  	header('Location: ./?view=main&try=request&error=1');
  }
}