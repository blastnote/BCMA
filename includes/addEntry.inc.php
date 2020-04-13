<?php

/*
This script adds an entry to the database and handles uploading ONE file into the uploads folder
(Needs to be rewritten for multiple input/ files)
*/

if (isset($_POST['addEntry-submit'])) {
	// Get connection
	require 'dbhA.inc.php';

	ini_set('display_errors',1);

	$Title = $_POST['Title'];
	$ADate = $_POST['ADate'];
	$LDate = strtotime($_POST['LDate']) !== '0000-00-00' ? NULL : $_POST['LDate'];
	$Desc = $_POST['Desc'];
	$Donor = $_POST['Donor'];
	$Amount = $_POST['Amount'];
	$D = $_POST['D'] ? 1 : 0;
	$AID = 'BCM.'.date('Y',strtotime($ADate)).'.'.preg_replace("/[^a-zA-Z]/", "", $Donor);
	$pic = '';
	$uploadMsg = NULL;

	if (empty($ADate) || empty($Title) || empty($Desc) || empty($Donor) || empty($Amount)) {
		header("Location: ../pages/newEntry.php?error=emptyfields");
		exit();
	}

	else {
		//check for existing Archival IDs with same year and donor
		$sql = "SELECT itemAID FROM active WHERE itemAID LIKE '".$AID.".%'";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/newEntry.php?error=sqlStatementError");
			exit();
		}
		else {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			$AID = $AID.'.'.($resultCheck+1);

			if (isset($_FILES['File']) && $_FILES['File']['size']>0) {
				print_r($_FILES['File']);
				$fileName = $_FILES['File']['name'];
				$fileTmpName = $_FILES['File']['tmp_name'];

				$fileExt = end(explode('.',$fileName));
				$allowed = array('jpg','jpeg','png');

				if ($_FILES['File']['error']>0) { $uploadMsg='upErr'; return; }
				if (!in_array(strtolower($fileExt),$allowed)) { $uploadMsg='typErr'; return; }
				if ($_FILES['File']['size']>16000000) { $uploadMsg='tooBig'; return; }

				$NewDir = '../uploads/'.$AID.'/'.$fileName;
				if (!is_dir('../uploads/'.$AID)) {
					mkdir('../uploads/'.$AID);
				}
				if (move_uploaded_file($fileTmpName, $NewDir)) { $uploadMsg = 'success'; $pic = $NewDir; }
				else { $uploadMsg = 'failMv'; }
			}
		
			$sql = "INSERT INTO `active` (`itemID`, `itemAID`, `itemADate`, `itemMPic`, `itemTitle`, `itemDesc`, `itemDonor`, `itemAmount`, `itemLDate`, `itemD`, `itemTags`, `itemMedia`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)";
			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {
				header("Location: ../pages/newEntry.php?error=sqlAddError");
				exit();
			}
			
			else {
				//actually adding item
				mysqli_stmt_bind_param($stmt, "ssssssssss", $AID, $ADate, $pic, $Title, $Desc, $Donor, $Amount, $LDate, $D, $pic);
				mysqli_stmt_execute($stmt);
				if (is_null($uploadMsg)) {
					header("Location: ../pages/newEntry.php?addEntry=success");
					exit();
				} else {
					header("Location: ../pages/newEntry.php?addEntry=success&upload=".$uploadMsg);
					exit();
				}
				
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

else {
	header("Location: ../pages/newEntry.php");
	exit();
}