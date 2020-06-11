<?php 
require 'utils/docStart.php';

// if (strpos($_SESSION['userPerms'], 'u') === false) {
//   header("Location: ../index.php?perm=archives");
//   exit();
// }
include_once '../includes/dbhl.inc.php';
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
		<div class="vw-100 pt-4">
		<div class="row px-2 vw-100 h-100">
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
		<!-- Add user model end -->

			<div class="col-2 border-right">
			<button id="modalActivate" type="button" class="btn btn-outline-warning waves-effect" data-toggle="modal" data-target="#addUser">Add User</button>
		<?php
		$result = mysqli_query($conn, "SELECT * FROM users");

        if (mysqli_num_rows($result) > 0) {
        	while ($row = mysqli_fetch_assoc($result)) {
				echo '<hr class="blue-grey lighten-2"><div><a class="white-text py-2" href="../pages/settings.php?id='.$row['idUsers'].'">'.$row['nameUsers'].'</a></div>';
			}}
		?>
			
			</div>
			<?php } ?>
			
			<!-- Main window -->
			<div class="col-10 white-text">
				<?php
				if (isset($_GET['id']) && $_GET['id'] !== false && !is_null($_GET['id']) && !(strpos($_SESSION['userPerms'], 'u') === false)) {
					$sql = "SELECT * FROM users WHERE idUsers = ".$_GET['id'].";";
					$result = mysqli_query($conn, $sql);
					$resultCheck = mysqli_num_rows($result);

					if ($resultCheck > 0) {
						$row = mysqli_fetch_assoc($result);

						$tempID = $row['idUsers'];
						$tempName = $row['nameUsers'];
						$tempUser = $row['uidUsers'];
						$tempPerms = $row['permUsers'];
					}
				} else {
					$tempID = $_SESSION['userid'];
					$tempName = $_SESSION['userName'];
					$tempUser = $_SESSION['userUid'];
					$tempPerms = $_SESSION['userPerms'];
				}
				?>
				<h1 class="ml-2 mt-2"><?php echo $tempName;?></h1>

				<?php if (!(strpos($_SESSION['userPerms'], 'u') === false)) { ?>
				<div class="d-flex justify-content-end"><button id="modalActivate" type="button" class="btn btn-outline-danger waves-effect btn-sm" data-toggle="modal" data-target="#deleteUser">Delete</button></div>

				<!-- Delete modal -->
				<div class="modal fade top" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm modal-frame modal-top" role="document">
					<div class="modal-content blue-grey darken-1">
					<div class="modal-body white-text">
						<h2>Are you sure you want to delete this user?</h2>
						<p>This is not able to be undone</p>
					</div>
					<div class="modal-footer">
						<form action="../includes/user.inc.php" method="post">
						<?php
							echo '<input type="hidden" name="id" value="'.$tempID.'">';
						?>
						<input type="submit" class="btn btn-danger" value="Yes" name="deleteUser-submit">
						</form>
						<button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
					</div>
					</div>
				</div>
				</div>
				<!-- Delete modal -->
				<?php } ?>
				<form action="../includes/user.inc.php" method="post">
            	<div class="modal-body mx-3">
					<div class="md-form mb-4">
						<?php echo '<input type="text" id="username" class="form-control mb-4 white-text" name="uid" value="'.$tempUser.'" required>'; ?>
						<label class="white-text" data-error="wrong" data-success="right" for="username">Username</label>
					</div>
					<div class="md-form mb-4">
						<?php echo '<input type="text" id="name" class="form-control mb-4 white-text" name="name" value="'.$tempName.'" required>'; ?>
						<label class="white-text" data-error="wrong" data-success="right" for="name">Name</label>
					</div>
				<?php if (!(strpos($_SESSION['userPerms'], 'u') === false)) { ?>
					<div class="mb-4 switch custom-control-inline">
						<label class="white-text">
							<?php 
								if (!(strpos($tempPerms, 'A') === false)) { echo '<input type="checkbox" name="editEntries" checked>'; }
								else { echo '<input type="checkbox" name="editEntries">'; }
							?>
							<span class="lever"></span> Can (delete/ edit/ add) entries
						</label>
					</div>
					<div class="mb-4 switch custom-control-inline">
						<label class="white-text">
							<?php 
								if (!(strpos($tempPerms, 'u') === false)) { echo '<input type="checkbox" name="editUsers" checked>'; }
								else { echo '<input type="checkbox" name="editUsers">'; }
							?>
							<span class="lever"></span> Can (delete/ edit/ add) users
						</label>
					</div>
				<?php } ?>
            	</div>
					<div class="d-flex justify-content-center">
						<?php echo '<input type="hidden" name="id" value="'.$tempID.'">'; ?>
						<input type="submit" class="btn btn-purple" name="updateUser-submit" value="Update">
					</div>
			  	</form>
			  
			  	<form action="../includes/user.inc.php" method="post">
            	<div class="modal-body mx-3">
					<div class="md-form mb-4">
						<input type="password" id="pwd" name="pwd" class="form-control mb-4 white-text" required>
						<label class="white-text" data-error="wrong" data-success="right" for="pwd">Password</label>
					</div>
					<div class="md-form mb-4">
						<input type="password" id="pwdRepeat" name="pwdRepeat" class="form-control mb-4 white-text" required>
						<label class="white-text" data-error="wrong" data-success="right" for="pwdRepeat">Password repeat</label>
					</div>
            	</div>
            	<div class="d-flex justify-content-center">
				<?php 
					echo '<input type="hidden" name="id" value="'.$tempID.'">';
					if ($_SESSION['userUid'] === $tempUser) { echo '<input type="submit" class="btn btn-purple" name="updatePwdUser-submit" value="Update Password">'; }
					else { echo '<input type="submit" class="btn btn-danger" name="updatePwdUser-submit" value="Force Password Change">'; }
				?>
            	</div>
          		</form>

			</div>
		</div>
		</div>
	</main>

    <!-- End -->
  <?php require 'utils/D_scripts.php'; ?>

</body>
</html>
