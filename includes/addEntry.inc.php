<?php
if (isset($_POST['addEntry-submit'])) {
	// Get connection
	require 'dbha.inc.php';

	//$AID = $_POST['AID'];
	$ADate = $_POST['ADate'];
	$LDate = strtotime($_POST['LDate']) !== '0000-00-00' ? NULL : $_POST['LDate'];
	$Title = $_POST['Title'];
	$Desc = $_POST['Desc'];
	$Donor = $_POST['Donor'];
	$Amount = $_POST['Amount'];
	$D = $_POST['D'] ? 1 : 0;
	$AID = 'BCM.'.date('Y',strtotime($ADate)).'.'.preg_replace("/[^a-zA-Z]/", "", $Donor);

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
		
			$sql = "INSERT INTO `active` (`itemID`, `itemAID`, `itemADate`, `itemMPic`, `itemTitle`, `itemDesc`, `itemDonor`, `itemAmount`, `itemLDate`, `itemD`, `itemTags`, `itemMedia`) VALUES (NULL, ?, ?, '', ?, ?, ?, ?, ?, ?, NULL, '')";
			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {
				header("Location: ../pages/newEntry.php?error=sqlAddError");
				exit();
			}
			
			else {
				//actually adding item
				mysqli_stmt_bind_param($stmt, "ssssssss", $AID, $ADate, $Title, $Desc, $Donor, $Amount, $LDate, $D);
				mysqli_stmt_execute($stmt);
				header("Location: ../pages/newEntry.php?addEntry=success");
				exit();
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