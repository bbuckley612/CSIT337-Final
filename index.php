<?php


/* MAIN SCRIPTS */

include "./main/database.php";
include "./main/authenticate.php";
include './main/helpers.php';


/* FILE ROUTING */

// Action Router
if (!empty($_GET['action'])) {
  include './actions/' . $_GET['action'] . ".php"; 
  mysqli_close($conn);
  exit();

// Root Directory Router
} else if (empty($_GET['view'])) {
  $url = $_SERVER['REQUEST_URI'];
  $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'view=main';
  header("Location: $url");
  mysqli_close($conn);
  exit();

// View Router
} else {

?>

  <!DOCTYPE html>
  <html lang="en">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

      <!-- Title and Favicon -->
      <title>PayPartner</title>

      <!-- Bootstrap CSS -->
      <link href="./assets/bootstrap.min.css" rel="stylesheet">

      <!-- Custom CSS -->
      <link href="./assets/custom.css" rel="stylesheet">

      <!-- jQuery -->
      <script src="./assets/jquery.min.js"></script>

      <!-- Custom JS -->
      <script src="./assets/custom.js"></script>
    </head>
    <body class="container">
      
      <?php
        include "./views/" . $_GET['view'] . ".php"; 
      ?>

      <!-- Bootstrap Bundle -->
      <script src="./assets/bootstrap.bundle.min.js"></script>
    </body>
  </html>

<?php

}

mysqli_close($conn);

?>