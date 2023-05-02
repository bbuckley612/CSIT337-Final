<?php

/**
 * AUTHENTICATE.PHP
 * This file is run with every server ping.
 * Ensure that the user is logged in. If not, redirect to login view.
 */

function goToLogin() {

  if ($_GET['view'] != "login" && 
      $_GET['view'] != "register" && 
      $_GET['action'] != "logout" && 
      $_GET['action'] != "authorize") {

    header("Location: ./?view=login");
    mysqli_close($conn);
    exit();
  }
}

if (!empty($_COOKIE['login'])) {
  $cookie = filter_input(INPUT_COOKIE, 'login', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_BACKTICK);
  $sql = "SELECT `id`, `first`, `last`, `email` FROM `users` WHERE `cookie` = '$cookie' LIMIT 1";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_first'] = $row['first'];
      $_SESSION['user_last'] = $row['last'];
      $_SESSION['user_email'] = $row['email'];
    }
  } else {
    goToLogin();
  }
} else {
  goToLogin();
}