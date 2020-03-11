<?php
if (isset($_POST['login-submit'])) {
	// Get connection
	require 'dbhl.inc.php';

	$username = $_POST['uid'];
	$password = $_POST['pwd'];

	if (empty($username) || empty($password)) {
		header("Location: ../pages/login.php?error=emptyfields&uid=".$username."");
		exit();
	}

	else {
		$sql = "SELECT * FROM users WHERE uidUsers=?;";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/login.php?error=sqlerror");
			exit();
		}

		else {
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			$row = mysqli_fetch_array($result);
			if (!empty($row)) {
				$pwdCheck = password_verify($password, $row['pwdUsers']);
				if ($pwdCheck == false) {
					header("Location: ../pages/login.php?error=wrongpwd");
					exit();
				}
				else if ($pwdCheck == true) {
					session_start();
					$_SESSION['userid'] = $row['idUsers'];
					$_SESSION['userUid'] = $row['uidUsers'];
					$_SESSION['userName'] = $row['nameUsers'];
					$_SESSION['userPerms'] = $row['permUsers'];

					header("Location: ../index.php");
					exit();
				} else {
					header("Location: ../pages/login.php?error=wrongpwd1");
					exit();
				}
			} else {
				header("Location: ../pages/login.php?error=nou");
				exit();
			}
		}
	}
}

else {
	header("Location: ../pages/login.php");
	exit();
}