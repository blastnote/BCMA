<?php
if (isset($_POST['addUser-submit'])) {
	// Get connection
	require 'dbhl.inc.php';

	$username = $_POST['uid'];
	$name = $_POST['name'];
	$password = $_POST['pwd'];
	$passwordRepeat = $_POST['pwdRepeat'];
	$perms = 'a'.($_POST['editEntries']?'A':'').($_POST['editUsers']?'u':'');

	if (empty($username) || empty($name) || empty($password) || empty($passwordRepeat)) {
		header("Location: ../pages/settings.php?error=emptyfields&uid=".$username."");
		exit();
	}

	// Valid username
	else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		header("Location: ../pages/settings.php?error=invaliduid&uid=".$username."&name=".$name."");
		exit();
	}

	else if (!preg_match("/^[a-zA-Z0-9]*$/", $name)) {
		header("Location: ../pages/settings.php?error=invalidname&uid=".$username."&name=".$name."");
		exit();
	}

	else if (!($password == $passwordRepeat)) {
		header("Location: ../pages/settings.php?error=passwordcheck&uid=".$username."&name=".$name."");
		exit();
	}

	else {
		$sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/settings.php?error=sqlerror");
			exit();
		}

		else { // Check if username taken
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);

			if ($resultCheck > 0) {
				header("Location: ../pages/settings.php?error=usertaken");
				exit();
			}

			else { // Actually creating user

				$sql = "INSERT INTO users (idUsers, uidUsers, nameUsers, permUsers, pwdUsers) VALUES (NULL,?, ?, ?, ?)";
				$stmt = mysqli_stmt_init($conn);

				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location: ../pages/settings.php?error=sqlerror");
					exit();
				}

				else {
					$hashedPwd = password_hash($password, PASSWORD_DEFAULT);

					mysqli_stmt_bind_param($stmt, "ssss", $username, $name, $perms, $hashedPwd);
					mysqli_stmt_execute($stmt);
					header("Location: ../pages/settings.php?addUser=success");
					exit();
				}
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

else {
	header("Location: ../pages/settings.php");
	exit();
}