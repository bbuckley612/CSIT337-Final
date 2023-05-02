<?php include './actions/logout.php'; ?>

<div class="col-12 col-sm-11 col-md-8 col-lg-6 col-xl-5 m-auto text-white">
  
  <img class="m-auto mt-5 d-block" style="border-radius: 50%; width: 100px; box-shadow: 0px 3px 8px 0px #000;" src="assets/logo.jpg">

  <div class="card card-dark py-4 px-5 my-4">
    <div class="card-body text-white">
      <h2 class="fw-bolder">Create Account</h2>
      <form action="./?action=authorize" method="POST">

        <table class="mt-3 mb-1 w-100">
          <tr class="row py-1">
            <td class="col-4">
              <label class="fw-bold" for="first">First Name:</label>
            </td>
            <td class="col-8">
              <input class="w-100" type="text" id="first" name="first" value="<?php echo $_SESSION['user_first']; ?>" required />
            </td>
          </tr>
          <tr class="row py-1">
            <td class="col-4">
              <label class="fw-bold" for="last">Last Name:</label>
            </td>
            <td class="col-8">
              <input class="w-100" type="text" id="last" name="last" value="<?php echo $_SESSION['user_last']; ?>" required />
            </td>
          </tr>
          <tr class="row py-1">
            <td class="col-4">
              <label class="fw-bold" for="email">Email:</label>
            </td>
            <td class="col-8">
              <input class="w-100" type="email" id="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" required />
            </td>
          </tr>
          <tr class="row py-1">
            <td class="col-4">
              <label class="fw-bold" for="password">Password:</label>
            </td>
            <td class="col-8">
              <input class="w-100" type="password" id="password" name="password" required />
            </td>
          </tr>
          <tr class="row py-1">
            <td class="col-4">
              <label class="fw-bold" for="retype">Re-type Password:</label>
            </td>
            <td class="col-8">
              <input class="w-100" type="password" id="retype" name="retype" required />
            </td>
          </tr>
        </table>

        <input type="hidden" name="create" value="1" required />
        <input type="submit" value="Register" class="btn btn-primary w-100 my-3 px-5 fw-bold" />

        <?php if (!empty($_GET['error']) && $_GET['error'] == 1) { ?>
          <div class="alert alert-danger" role="alert">
            Invalid email provided.
          </div>
        <?php } ?>

        <?php if (!empty($_GET['error']) && $_GET['error'] == 2) { ?>
          <div class="alert alert-danger" role="alert">
            Passwords didn't match. Try again?
          </div>
        <?php } ?>

        <?php if (!empty($_GET['error']) && $_GET['error'] == 3) { ?>
          <div class="alert alert-danger" role="alert">
            It looks like you already have an account!
          </div>
        <?php } ?>

        <p class="mt-3" style="font-weight: 300; font-size: 11pt;">
          Already have an account?&nbsp;&nbsp;<a href="./?view=login" style="color: #70b9ff;">Log in ›</a>
        </p>

      </form>
    </div>
  </div>

  <p class="text-center" style="color: #111; opacity: 0.8; font-size: 11pt;">
    Copyright &copy; <?php echo date('Y'); ?> - xxx
  </p>

</div>