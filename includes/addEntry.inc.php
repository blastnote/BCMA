<?php
if (isset($_POST['addEntry-submit'])) {
	// Get connection
	require 'dbha.inc.php';

	$AID = $_POST['AID'];
	$ADate = $_POST['ADate'];
	$LDate = strtotime($_POST['LDate']) !== '0000-00-00' ? NULL : $_POST['LDate'];
	$Title = $_POST['Title'];
	$Desc = $_POST['Desc'];
	$Donor = $_POST['Donor'];
	$Amount = $_POST['Amount'];
	$D = $_POST['D'] ? 1 : 0;

	if (empty($AID) || empty($ADate) || empty($Title) || empty($Desc) || empty($Donor) || empty($Amount)) {
		header("Location: ../pages/newEntry.php?error=emptyfields");
		exit();
	}

	else {
		$sql = "INSERT INTO `active` (`itemID`, `itemAID`, `itemADate`, `itemMPic`, `itemTitle`, `itemDesc`, `itemDonor`, `itemAmount`, `itemLDate`, `itemD`, `itemTags`, `itemMedia`) VALUES (NULL, ?, ?, '', ?, ?, ?, ?, ?, ?, '', '')";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/newEntry.php?error=sqlerror");
			exit();
		}

		else {
			mysqli_stmt_bind_param($stmt, "ssssssss", $AID, $ADate, $Title, $Desc, $Donor, $Amount, $LDate, $D);
			mysqli_stmt_execute($stmt);
			header("Location: ../pages/newEntry.php?addEntry=success");
			exit();
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

else {
	header("Location: ../pages/newEntry.php");
	exit();
}