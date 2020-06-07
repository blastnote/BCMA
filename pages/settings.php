<?php 
require 'utils/docStart.php';

// if (strpos($_SESSION['userPerms'], 'u') === false) {
//   header("Location: ../index.php?perm=archives");
//   exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require 'utils/D_head.php'; ?>
</head>

<body>
	<?php require "utils/nav.php"; ?>

	<!-- Main body -->
	<main class="elegant-color min-vh-100 vw-100 pt-5">
		<div class="container no-gutters vw-100 pt-4">
		
		<?php if (!(strpos($_SESSION['userPerms'], 'u') === false)) { ?>
		<!-- Add user model -->
		<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content blue-grey darken-1">
			<div class="modal-header text-center">
				<h4 class="modal-title w-100 font-weight-bold white-text">Add User</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span class="white-text" aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="../includes/user.inc.php" method="post">
            <div class="modal-body mx-3">
				<div class="md-form mb-4">
					<input type="text" id="username" class="form-control mb-4 white-text" name="uid" required>
					<label class="white-text" data-error="wrong" data-success="right" for="username">Username</label>
				</div>
				<div class="md-form mb-4">
					<input type="text" id="name" class="form-control mb-4 white-text" name="name" required>
					<label class="white-text" data-error="wrong" data-success="right" for="name">Name</label>
				</div>
				<div class="md-form mb-4">
					<input type="password" id="pwd" name="pwd" class="form-control mb-4 white-text" required>
					<label class="white-text" data-error="wrong" data-success="right" for="pwd">Password</label>
				</div>
				<div class="md-form mb-4">
					<input type="password" id="pwdRepeat" name="pwdRepeat" class="form-control mb-4 white-text" required>
					<label class="white-text" data-error="wrong" data-success="right" for="pwdRepeat">Password repeat</label>
				</div>

				<div class="mb-4 switch custom-control-inline">
					<label class="white-text">
						<input type="checkbox" name="editEntries">
						<span class="lever"></span> Can (delete/ edit/ add) entries
					</label>
				</div>
				<div class="mb-4 switch custom-control-inline">
					<label class="white-text">
						<input type="checkbox" name="editUsers">
						<span class="lever"></span> Can (delete/ edit/ add) users
					</label>
				</div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
              <input type="submit" class="btn btn-purple" name="addUser-submit" value="Add User">
            </div>
          	</form>
		</div>
		</div>
		</div>
		<!-- Add user model -->
		<?php } ?>
			<?php 
				if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
					echo '<div class="col info-color-dark">';
					echo '<button id="modalActivate" type="button" class="btn btn-outline-warning waves-effect btn-sm" data-toggle="modal" data-target="#addUser">Add User</button>';
				}
			?>
			</div>
			<div class="col info-color">
				
			</div>
		</div>
	</main>

    <!-- End -->
  <?php require 'utils/D_scripts.php'; ?>

</body>
</html>
