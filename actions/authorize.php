<?php

/**
 * LOGOUT.PHP
 * This file requires POST data. It handles all login and new account requests.
 * - Request data is sanitized and validated
 * - Database queries:
 * 	- For new account, SQL INSERT is used.
 * 	- For login, SQL SELECT is used.
 * - On success, login cookie is added to browser
 * 	- User record is updated with login cookie data and timestamp.
 */


/* COOKIE FUNCTION */

function setLoginCookie($email) {
  global $conn;
  $data = getToken(40);

  // Setting cookie and updating database record
  // Both browser and server need to have the same value
  setcookie("login", $data, time() + (86400 * 30), "/");
  $sql = "UPDATE users SET `cookie` = '$data', `updated` = CURRENT_TIMESTAMP WHERE `email` = '$email'";

  if ($conn->query($sql) === TRUE) {

  	// Reset session values
  	session_destroy();
  	session_start();

  	// Redirect to index
		header('Location: ./');

	// Error updating record
  } else {
	echo "Error updating record: " . $conn->error;
  }
}


/* HANDLE POST */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	// Data sanitization
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);
  $_SESSION['user_email'] = $email;


  /* HANDLE NEW ACCOUNT */

  if (!empty($_POST['create'])) {

  	// More data sanitization
  	$first = filter_input(INPUT_POST, 'first', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  	$last = filter_input(INPUT_POST, 'last', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  	$retype = filter_input(INPUT_POST, 'retype', FILTER_UNSAFE_RAW);
  	$_SESSION['user_first'] = $first;
  	$_SESSION['user_last'] = $last;

  	// Data validation
  	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
  	  if ($password === $retype) {

  	  	// Salt and hash password
  	    $hash = password_hash($password, PASSWORD_BCRYPT);

  	    // Unset password vars
  	    unset($password);
  	    unset($retype);

  	    // Try to execute SQL statement
		    try {
		      mysqli_query($conn, "INSERT INTO `users` (`first`, `last`, `email`, `hash`) VALUES ('$first', '$last', '$email', '$hash')");

		      // New account is authorized! Let's call the cookie function
		      setLoginCookie($email);
		    } 

		    // MySQL error; most likely user already exists
		    catch (mysqli_sql_exception $exception) {
		      header('Location: ./?view=register&error=3');
		    }

		  // Passwords didn't match
		  } else {
		  	header('Location: ./?view=register&error=2');
		  }

		// Invalid email format
  	} else {
  	  header('Location: ./?view=register&error=1');
  	}


  /* HANDLE LOGIN */

  } else {

  	// Try to find user's record in database
		$sql = "SELECT * FROM `users` WHERE `email` = '$email' LIMIT 1";
		$result = $conn->query($sql);
		$record = $result->fetch_assoc();

		if ($record !== null) {
		  if (password_verify($password, $record['hash'])) {

		  	// Unset password var
  	    unset($password);

		  	// Login is authorized! Let's call the cookie function
		  	setLoginCookie($email);

		  // Password doesn't match hash in database
		  } else {
		  	header('Location: ./?view=login&error=2');
		  }

		// Record not found
		} else {
		  header('Location: ./?view=login&error=1');
		}
  }
}