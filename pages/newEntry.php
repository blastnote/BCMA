<?php require 'utils/docStart.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require '../pages/utils/D_head.php'; ?>
</head>

<body>
<?php require '../pages/utils/nav.php'; ?>
        
<!--Main layout-->
<main class="elegant-color min-vh-100 pt-5">

  <div class="container-fluid mt-4">
  	<form action="../includes/addEntry.inc.php" method="POST" enctype="multipart/form-data">
				<!-- <input type="text" class="form-control mb-4" name="AID" placeholder="Item archival ID" required> -->
				
				<input type="text" class="form-control mb-4" name="Title" placeholder="Item Title" required>

				<input type="date" class="form-control mb-4" name="ADate" placeholder="Accession date" required>

				<input type="date" class="form-control mb-4" name="LDate" placeholder="Loan date">
				
				<input type="text" class="form-control mb-4" name="Desc" placeholder="Item description" required>

				<input type="text" class="form-control mb-4" name="Donor" placeholder="Item Donor" required>

				<input type="number" class="form-control mb-4" name="Amount" placeholder="Item Amount" required>

				<input type="file" class="fmb-4 white-text" name="File" accept="image/*">

				<div class="switch custom-control-inline">
					<label class="white-text">
              			<input type="checkbox" name="D">
              			<span class="lever"></span> Deaccessioned
            		</label>
            	</div>

				<button class="btn btn-info btn-block my-4" type="submit" name="addEntry-submit">Add entry</button>
			</form>
  </div>

</main>
<!--Main layout-->

    <!-- End -->
  <?php require '../pages/utils/D_scripts.php'; ?>

</body>
</html>
