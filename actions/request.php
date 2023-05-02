<?php

/**
 * REQUEST.PHP
 * This file requires POST data. It handles all invoice creations.
 * - Request data is sanitized and validated
 * - xxx
 * - On success, xxx
 */


/* HANDLE POST */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Data sanitization
	$uid = $_SESSION['uid'];
	$recipient = $_SESSION['email'];
  $sender = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
	$desc = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);

	// Data validation
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

    // Try to execute SQL statement
    try {
      mysqli_query($conn, "INSERT INTO `invoices` (`user_id`, `sender`, `recipient`, `amount`, `desc`, `status`) VALUES ($uid, '$sender', '$recipient', '$amount', '$desc', 1)");

      // New account is authorized! Let's call the cookie function
      header('Location: ./?view=main&try=request&success=1');
    } 

    // MySQL error; most likely user already exists
    catch (mysqli_sql_exception $exception) {
      header('Location: ./?view=main&try=request&error=2');
    }

  // Passwords didn't match
  } else {
  	header('Location: ./?view=main&try=request&error=1');
  }
}