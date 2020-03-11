<?php require 'utils/docStart.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require 'utils/D_head.php'; ?>
</head>

<body>
	<?php require "utils/nav.php"; ?>

	<!-- Main body -->
	<main class="elegant-color min-vh-100 mt-4 pt-5">
		<div class="container-fluid">
			<form action="../includes/addUser.inc.php" method="POST">
				<input type="text" class="form-control mb-4" name="uid" placeholder="Username" required>
				<input type="text" class="form-control mb-4" name="name" placeholder="Name" required>
				<input type="password" name="pwd" class="form-control mb-4" placeholder="Password" required>
				<input type="password" name="pwdRepeat" class="form-control mb-4" placeholder="Password verification" required>
				<button class="btn btn-info btn-block my-4" type="submit" name="addUser-submit">Add user</button>
			</form>
		</div>
	</main>

    <!-- End -->
  <?php require 'utils/D_scripts.php'; ?>

</body>
</html>
