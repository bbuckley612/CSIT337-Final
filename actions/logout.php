<?php

/**
 * LOGOUT.PHP
 * When this file is included or fetched by browser, user will be logged out.
 * - Login cookie is removed from browser
 * - Cookie string is expunged from database
 * - Session data is destroyed for good measure
 */

if (!empty($_COOKIE['login'])) {
	$cookie = filter_input(INPUT_COOKIE, 'login', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
	$sql = "UPDATE `users` SET `cookie` = NULL WHERE `cookie` = '$cookie' LIMIT 1";
	if ($conn->query($sql) === TRUE) {
	  setcookie("login", "", 0, "/");
	  session_destroy();
	  if (str_contains($_SERVER['REQUEST_URI'], "logout")) {
	  	header('Location: ./?view=login');
	  }
	} else {
	  echo "Error logging out: " . $conn->error;
	}
}