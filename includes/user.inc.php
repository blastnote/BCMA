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

if (isset($_POST['updateUser-submit'])) {
	// Get connection
	require 'dbhl.inc.php';
	session_start();

	$username = $_POST['uid'];
	$name = $_POST['name'];

	if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
		$id = $_POST['id'];
		$perms = 'a'.($_POST['editEntries']?'A':'').($_POST['editUsers']?'u':'');
	} else { $id = $_SESSION['userid']; }

	if (empty($username) || empty($name)) {
		if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
			header("Location: ../pages/settings.php?error=emptyfields&id=".$id);
			exit();
		} else {
			header("Location: ../pages/settings.php?error=emptyfields");
			exit();
		}
	}

	// Valid username
	else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
		if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
			header("Location: ../pages/settings.php?error=invaliduid&id=".$id);
			exit();
		} else {
			header("Location: ../pages/settings.php?error=invaliduid");
			exit();
		}
	}

	else {
		$result = mysqli_query($conn, "SELECT * FROM users");

		if (mysqli_num_rows($result) > 0) {
        	while ($row = mysqli_fetch_assoc($result)) {
				if (!($row['idUsers'] === $id) && ($row['uidUsers'] === $username)) {
					if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
						header("Location: ../pages/settings.php?id=".$id."&error=usertaken");
						exit();
					} else {
						header("Location: ../pages/settings.php?error=usertaken");
						exit();
					}
				}
			}
		}

		// Actually updating user
		$sql = "UPDATE `users` SET `uidUsers` = ?, `nameUsers` = ? WHERE idUsers = ".$id.";";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/settings.php?id=".$id."&error=sqlUpdateError");
			exit();
		}
		
		else {
			//actually adding item
			mysqli_stmt_bind_param($stmt, "ss", $username, $name);
			mysqli_stmt_execute($stmt);
			
			if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
				$sql = "UPDATE `users` SET `permUsers` = ? WHERE idUsers = ".$id.";";
				$stmt = mysqli_stmt_init($conn);

				if (!mysqli_stmt_prepare($stmt, $sql)) {
					header("Location: ../pages/settings.php?id=".$id."&error=sqlUpdateError");
					exit();
				}

				else {
					//actually adding item
					mysqli_stmt_bind_param($stmt, "s", $perms);
					mysqli_stmt_execute($stmt);

					header("Location: ../pages/settings.php?id=".$id."&updateNamesAP=success");
					exit();
				}
			}

			header("Location: ../pages/settings.php?updateNames=success");
			exit();
		}
	}
}

if (isset($_POST['updatePwdUser-submit'])) {
	// Get connection
	require 'dbhl.inc.php';
	session_start();

	$password = $_POST['pwd'];
	$passwordRepeat = $_POST['pwdRepeat'];

	if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
		$id = $_POST['id'];
	} else { $id = $_SESSION['userid']; }

	if (empty($password) || empty($passwordRepeat)) {
		header("Location: ../pages/settings.php?error=emptyfields");
		exit();
	}

	else if (!($password == $passwordRepeat)) {
		header("Location: ../pages/settings.php?error=passwordcheck&uid=".$username."&name=".$name."");
		exit();
	}

	else {
		$sql = "UPDATE `users` SET `pwdUsers` = ? WHERE idUsers = ".$id.";";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
				header("Location: ../pages/settings.php?id=".$id."&error=sqlerror");
				exit();
			} else {
				header("Location: ../pages/settings.php?error=sqlerror");
				exit();
			}
		}

		else {
			$hashedPwd = password_hash($password, PASSWORD_DEFAULT);

			mysqli_stmt_bind_param($stmt, "s", $hashedPwd);
			mysqli_stmt_execute($stmt);

			if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
				header("Location: ../pages/settings.php?id=".$id."&updatepwd=success");
				exit();
			} else {
				header("Location: ../pages/settings.php?updatepwd=success");
				exit();
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

if (isset($_POST['deleteUser-submit'])) {
	// Get connection
	require 'dbhl.inc.php';
	session_start();

	if (!(strpos($_SESSION['userPerms'], 'u') === false)) {
		$id = $_POST['id'];

		$sql = "DELETE FROM users WHERE idUsers = ".$id;
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/entry.php?item=".$id."&error=sqlStatementError");
			exit();
		} else {
			mysqli_stmt_execute($stmt);

			header("Location: ../pages/settings.php?delete=success");
			exit();
		}
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	else {
		header("Location: ../pages/settings.php?error=403");
		exit();
	}
}

else {
	header("Location: ../pages/settings.php");
	exit();
}