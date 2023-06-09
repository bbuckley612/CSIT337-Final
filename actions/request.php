<?php

/**
 * REQUEST.PHP
 * This file requires POST data. It handles all invoice creations.
 * - Request data is sanitized and validated
 * - Database queries:
 *  - Recipient's user id is selected from database
 *  - New invoice is inserted into database
 * - On success, redirect to success message
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Data sanitization
	$user_id = $_SESSION['user_id'];
  $recipient = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
	$description = filter_input(INPUT_POST, 'desc', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);

	// Data validation
	if (filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
    $amount = convertToValidPrice($amount);
    if ($amount) {
      if ($amount) {
      
        // Try to execute SQL statements
        try {
          $result = mysqli_query($conn, "SELECT id FROM users WHERE email = '$recipient' LIMIT 1");
          $recipient_id = mysqli_fetch_row($result)[0];
          mysqli_query($conn, "INSERT INTO `invoices` (`user_id`, `recipient_id`, `amount`, `description`, `status`) VALUES ($user_id, $recipient_id, '$amount', '$description', 1)");

          // New invoice created! Let's redirect to success message
          header('Location: ./?view=main&try=request&success=true');
        }

        // MySQL error; most likely recipient not found
        catch (mysqli_sql_exception $exception) {
          header('Location: ./?view=main&try=request&error=user');
        }

      // Invalid request description
      } else {
        header('Location: ./?view=main&try=request&error=description');
      }

    // Invalid request amount
    } else {
      header('Location: ./?view=main&try=request&error=amount');
    }

  // Invalid email address format
  } else {
  	header('Location: ./?view=main&try=request&error=format');
  }
}