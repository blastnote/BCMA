<?php
if (isset($_SESSION['userid'])) {
  header("Location: ../index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require 'utils/D_head.php'; ?>
</head>

<body>
    <!-- Start your project here-->
    <div class="d-flex justify-content-center" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url(../img/bg2.png); background-repeat: no-repeat; background-size: cover; height: 100vh; align-content: center;">

      <div class="row align-content-center">
        <div class="card">
          <form class="border border-light p-5 needs-validation" action="../includes/login.inc.php" method="POST" novalidate>

            <p class="h4 mb-4 text-center">Sign in</p>

            <input type="text" class="form-control mb-4" name="uid" placeholder="Username" required>

            <input type="password" name="pwd" class="form-control mb-4" placeholder="Password" required>

            <button class="btn btn-info btn-block my-4" type="submit" name="login-submit">Sign in</button>
          </form>
        </div>
      </div>
    </div>

    <!-- End -->
  <!-- Core scripts -->
  <?php require 'utils/D_scripts.php'; ?>

</body>
</html>
