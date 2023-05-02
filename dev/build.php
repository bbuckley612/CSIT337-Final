<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (empty($_GET['error']) && empty($_GET['run'])) {
  try {
    $conn = mysqli_connect("localhost", "root", "");
  } catch (mysqli_sql_exception $exception) {
    header("Location: ./build.php?error=1");
    exit();
  }
}

if (!empty($_GET['run']) && $_GET['run'] == 1 && !empty($_POST['user'])) {
  try {
    $conn = mysqli_connect("localhost", $_POST['user'], $_POST['pass']);
    mysqli_set_charset($conn, "utf8");
	$sql = file_get_contents("paypartner-init.sql");
	if (mysqli_multi_query($conn, $sql)) {
	  header("Location: ./success.php");
  	  exit();
	} else {
	  header("Location: ./build.php?error=3");
      exit();
	}
  } catch (mysqli_sql_exception $exception) {
  	header("Location: ./build.php?error=2");
    exit();
  }
}

?>

<!DOCTYPE html>
<html>
<title>Build Database</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body class="w3-container">

<h1>Welcome!</h1>

<?php if (!empty($_GET['error']) && $_GET['error'] == 2) { ?>
<div class="w3-panel w3-pale-red w3-border">
  <h3>Uh oh!</h3>
  <p>We couldn't connect to your MySQL server using those credentials.</p>
</div>
<?php } else if (!empty($_GET['error']) && $_GET['error'] == 3) { ?>
<div class="w3-panel w3-pale-red w3-border">
  <h3>Uh oh!</h3>
  <p>An unexpected SQL error occurred.</p>
</div>
<?php } ?>

<?php if (!empty($_GET['error'])) { ?>
<p>Use the form below to build our database.</p>
<form action="?run=1" method="POST">
  <label>MySQL Username</label>
  <input type="text" name="user" value="root" />
  <br />
  <label>MySQL Password</label>
  <input type="password" name="pass" value="" />
  <br />
  <button type="submit">Build Database</button>
</form>
<?php } else { ?>
<p>Use the button below to build our database.</p>
<form action="?run=1" method="POST">
  <input type="hidden" name="user" value="root" />
  <input type="hidden" name="pass" value="" />
  <button type="submit">Build Database</button>
</form>
<?php } ?>

<hr>

<p>Alternatively, you can run <a href="paypartner-init.sql">our SQL file</a> manually.</p>

</body>
</html>