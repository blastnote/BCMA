<?php

/*
This script adds an entry to the database and handles uploading ONE file into the uploads folder upon first adding item.
update functions allow for multiple files to be uploaded
(Needs to be rewritten for file deletion without distroying entry)
*/

if (isset($_POST['addEntry-submit'])) {
	// Get connection
	require 'dbhA.inc.php';

	$Title = $_POST['Title'];
	$ADate = $_POST['ADate'];
	$LDate = strtotime($_POST['LDate']) !== '0000-00-00' ? NULL : $_POST['LDate'];
	$Desc = $_POST['Desc'];
	$Donor = $_POST['Donor'];
	$Amount = $_POST['Amount'];
	$D = $_POST['D'] ? 1 : 0;
	$AID = 'BCM.'.date('Y',strtotime($ADate)).'.'.preg_replace("/[^a-zA-Z]/", "", strtolower($Donor));
	$pic = '';
	$uploadMsg = NULL;

	if (empty($ADate) || empty($Title) || empty($Desc) || empty($Donor) || empty($Amount)) {
		header("Location: ../pages/newEntry.php?error=emptyfields");
		exit();
	}

	else {
		//check for existing Archival IDs with same year and donor
		$sql = "SELECT * FROM active WHERE itemAID LIKE '".$AID.".%'";
		$result = mysqli_query($conn, $sql);
		//checks for gaps in similar ids
        if (mysqli_num_rows($result) > 0) {
			$i = 0;
          	while ($row = mysqli_fetch_assoc($result)) { $temp[$i] = $row['itemAID']; $i++; }
			$i = 1;
			while (in_array($AID.'.'.$i, $temp)) { $i++; }
			$AID = $AID.'.'.$i;
		} else { $AID = $AID.'.1'; }

		if (isset($_FILES['File']) && $_FILES['File']['size']>0) {
			$fileName = $_FILES['File']['name'];
			$fileTmpName = $_FILES['File']['tmp_name'];

			$fileExt = end(explode('.',$fileName));
			$allowed = array('jpg','jpeg','png','gif');

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
	
		$sql = "INSERT INTO `active` (`itemID`, `itemAID`, `itemADate`, `itemTitle`, `itemDesc`, `itemDonor`, `itemAmount`, `itemLDate`, `itemD`, `itemTags`, `itemMedia`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?)";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/newEntry.php?error=sqlAddError");
			exit();
		}
		
		else {
			//actually adding item
			mysqli_stmt_bind_param($stmt, "sssssssss", $AID, $ADate, $Title, $Desc, $Donor, $Amount, $LDate, $D, $pic);
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
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

elseif (isset($_POST['updateEntry-submit'])) {
	require 'dbhA.inc.php';

	$Title = $_POST['Title'];
	$ADate = $_POST['ADate'];
	$LDate = strtotime($_POST['LDate']) !== '0000-00-00' ? NULL : $_POST['LDate'];
	$Desc = $_POST['Desc'];
	$Donor = $_POST['Donor'];
	$Amount = $_POST['Amount'];
	$D = $_POST['D'] ? 1 : 0;
	$AID = 'BCM.'.date('Y',strtotime($ADate)).'.'.preg_replace("/[^a-zA-Z]/", "", strtolower($Donor));
	$media = '';

	$id = $_POST['id'];
	$OAID = $_POST['OAID'];

	if (empty($Title) || empty($Desc) || empty($Donor) || empty($ADate) || empty($Amount)) {
		header("Location: ../pages/entry.php?item=".$id."&error=emptyfields");
		exit();
	} else {
		//check for existing Archival IDs with same year and donor
		$sql = "SELECT * FROM active WHERE itemAID LIKE '".$AID.".%'";
		$result = mysqli_query($conn, $sql);
		//checks for gaps in similar ids
        if (mysqli_num_rows($result) > 0) {
			$i = 0;
          	while ($row = mysqli_fetch_assoc($result)) { 
				if ($OAID !== $row['itemAID']) { $temp[$i] = $row['itemAID']; $i++; }
			}
			$i = 1;
			while (in_array($AID.'.'.$i, $temp)) { $i++; }
			$AID = $AID.'.'.$i;
		} else { $AID = $AID.'.1'; }

		if ($AID !== $OAID) {
			$sql = "SELECT itemMedia FROM active WHERE itemID = ".$id;
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result)) {
				$media = mysqli_fetch_assoc($result);
				$media = str_replace($OAID, $AID, $media['itemMedia'], $j);

				if (is_dir('../uploads/'.$OAID)) {
					rename('../uploads/'.$OAID, '../uploads/'.$AID);
				}
			}

			$sql = "UPDATE `active` SET `itemAID` = ?, `itemADate` = ?, `itemTitle` = ?, `itemDesc` = ?, `itemDonor` = ?, `itemAmount` = ?, `itemLDate` = ?, `itemD` = ?, `itemTags` = NULL, `itemMedia` = ? WHERE itemID = ".$id.";";
			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {
				header("Location: ../pages/entry.php?item=".$id."&error=sqlUpdateError");
				exit();
			}
			
			else {
				//actually adding item
				mysqli_stmt_bind_param($stmt, "sssssssss", $AID, $ADate, $Title, $Desc, $Donor, $Amount, $LDate, $D, $media);
				mysqli_stmt_execute($stmt);
				
				header("Location: ../pages/entry.php?item=".$id."&update=success");
				exit();
			}
		} else {
			$sql = "UPDATE `active` SET `itemADate` = ?, `itemTitle` = ?, `itemDesc` = ?, `itemDonor` = ?, `itemAmount` = ?, `itemLDate` = ?, `itemD` = ?, `itemTags` = NULL WHERE itemID = ".$id.";";
			$stmt = mysqli_stmt_init($conn);

			if (!mysqli_stmt_prepare($stmt, $sql)) {
				header("Location: ../pages/entry.php?item=".$id."&error=sqlUpdateError");
				exit();
			}
			
			else {
				//actually adding item
				mysqli_stmt_bind_param($stmt, "sssssss", $ADate, $Title, $Desc, $Donor, $Amount, $LDate, $D);
				mysqli_stmt_execute($stmt);
				
				header("Location: ../pages/entry.php?item=".$id."&update=success");
				exit();
			}
		}
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

elseif (isset($_POST['deleteEntry-submit'])) {
	require 'dbhA.inc.php';

	$id = $_POST['id'];
	$AID = $_POST['AID'];

	$sql = "DELETE FROM active WHERE itemID = ".$id;
	$stmt = mysqli_stmt_init($conn);

	if (!mysqli_stmt_prepare($stmt, $sql)) {
		header("Location: ../pages/entry.php?item=".$id."&error=sqlStatementError");
		exit();
	} else {
		mysqli_stmt_execute($stmt);

		//remove media
		array_map('unlink', glob("../uploads/".$AID."/*.*"));
		rmdir("../uploads/".$AID);

		header("Location: ../pages/archives.php?delete=success");
		exit();
	}
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
}

elseif (isset($_POST['addFiles-submit'])) {
	require 'dbhA.inc.php';

	$id = $_POST['id'];
	$uploadMsg = '';
	$countfiles = count($_FILES['file']['name']);

	$sql = "SELECT * FROM active WHERE itemID = ".$id;
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result)) {
		$row = mysqli_fetch_assoc($result);
 
		$media = $row['itemMedia'];
		$AID = $row['itemAID'];

		// Looping all files
		for($i=0;$i<$countfiles;$i++){
			$fileName = $_FILES['file']['name'][$i];
			$fileTmpName = $_FILES['file']['tmp_name'][$i];
			
			$fileExt = end(explode('.',$fileName));
			$allowed = array('jpg','jpeg','png','gif');

			// Error handling
			if ($_FILES['file']['error'][$i]>0) { $uploadMsg='upErr'; return; }
			if (!in_array(strtolower($fileExt),$allowed)) { $uploadMsg='typErr'; return; }
			if ($_FILES['file']['size'][$i]>16000000) { $uploadMsg='tooBig'; return; }

			$NewDir = '../uploads/'.$AID.'/'.$fileName;

			if (!is_dir('../uploads/'.$AID)) { mkdir('../uploads/'.$AID); }
			if (move_uploaded_file($fileTmpName, $NewDir)) { $uploadMsg = 'success'; }
			else { $uploadMsg = 'failMv'; }

			if (is_null($media) || $media == '') { $media = $NewDir; } 
			else { $media = $media.'|'.$NewDir; }
		}

		$sql = "UPDATE `active` SET `itemMedia` = ? WHERE itemID = ".$id.";";
		$stmt = mysqli_stmt_init($conn);

		if (!mysqli_stmt_prepare($stmt, $sql)) {
			header("Location: ../pages/entry.php?item=".$id."&error=sqlAddFileError");
			exit();
		}
		
		else {
			//actually adding item
			mysqli_stmt_bind_param($stmt, "s", $media);
			mysqli_stmt_execute($stmt);
			
			header("Location: ../pages/entry.php?item=".$id."&addFile=success");
			exit();
		}
	} else {
		header("Location: ../pages/entry.php?item=".$id."&error=noData");
		exit();
	}
} elseif (isset($_POST['deleteFiles-submit'])) {
	require 'dbhA.inc.php';

	$id = $_POST['id'];

	$sql = "SELECT * FROM active WHERE itemID = ".$id;
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result)) {
		$row = mysqli_fetch_assoc($result);
		$deleteTbl = [];
		$media = explode('|',$row['itemMedia']);
		//bookmark
	} else {
		header("Location: ../pages/entry.php?item=".$id."&error=noData");
		exit();
	}

} else { // Exit from no submition data
	header("Location: ../index.php");
	exit();
}