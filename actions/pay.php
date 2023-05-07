<?php

/**
 * PAY.PHP
 * This file requires POST data. It handles all P2P payments.
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
          $recipient_result = mysqli_query($conn, "SELECT a.id FROM users AS u
                                        LEFT JOIN accounts AS a ON a.user_id = u.id
                                        WHERE u.email = '$recipient' AND a.priority = 1 LIMIT 1");
          
          $user_result = mysqli_query($conn, "SELECT a.id FROM users AS u
                                        LEFT JOIN accounts AS a ON a.user_id = u.id
                                        WHERE u.id = $user_id AND a.priority = 1 LIMIT 1");

          $recipient_acct = mysqli_fetch_row($recipient_result)[0];
          $user_acct = mysqli_fetch_row($user_result)[0];

          mysqli_query($conn, "INSERT INTO `transactions` (`account_id`, `recipient_id`, `amount`, `description`) VALUES ($user_acct, $recipient_acct, '$amount', '$description')");

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