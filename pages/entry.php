<?php 
require 'utils/docStart.php' ;

if (strpos($_SESSION['userPerms'], 'a') === false) {
  header("Location: ../index.php");
  exit();
}

include_once '../includes/dbhA.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require '../pages/utils/D_head.php'; ?>
</head>

<body>
<?php
require '../pages/utils/nav.php'; 

$sql = "SELECT * FROM active WHERE itemID = ".$_GET['item'].";";
$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);
?>
        
<!--Main layout-->
<main class="elegant-color min-vh-100 pt-5 pb-5">

  <div class="container no-gutters mt-3 pt-3">
    <?php
    if ($resultCheck > 0) {
      $row = mysqli_fetch_assoc($result);
    ?>

    <div class="float-sm-left">
    <!-- info sidebar -->
    <div class="flex-sm-column white-text">
      <div class="card card-side card-cascade mt-2 mr-5 mb-4">
        <!-- Card image -->
        <?php if ($row['itemMPic'] !== "") {
            echo '<img class="card-img-top" src="'.$row['itemMPic'].'" alt="Card image cap">';
          } else {
            echo '<img class="card-img-top" src="/img/noimage.png" alt="Card image cap">';
          }?>
          <!-- Card content -->
          <div class="card-body card-body-cascade stylish-color text-left">
            <p><?php echo $row['itemAID']; ?></p>
            <hr class="blue-grey lighten-2">
            <p>Accession date: <?php echo $row['itemADate']; ?></p>

            <?php if ($row['itemLDate']) { ?> 
            <hr class="blue-grey lighten-2">
            <p>On loan until: <?php echo $row['itemLDate']; ?></p>
            <?php } ?>
            
            <?php if ($row['itemAmount']>1) { ?> 
            <hr class="blue-grey lighten-2">
            <p>Number of items: <?php echo $row['itemAmount']; ?></p>
            <?php } ?>

            <?php if (!is_null($row['itemTags'])) { 
              $s = explode("|", $row['itemTags']);
              echo '<hr class="blue-grey lighten-2">';
              $j = sizeof($s);
              
              for ($i=0; $i < $j; $i++) { 
                  echo '<div class="chip secondary-color-dark white-text">'.$s[$i].'</div>';
              }
            } ?>
          </div>
          <!-- Card footer -->
          <?php if ($row['itemD'] == 1) { ?> 
          <div class="card-footer stylish-color-dark text-light text-center ">
            <p>Deaccessioned</p>
          </div>
          <?php } ?>
        </div>

      </div>
      <!-- Card end -->
    </div>

    <!-- main info -->
    <div class="flex-lg-column w-auto white-text item-main float-xl-left">

        <h1><?php echo $row['itemTitle']; ?></h1>
        <hr class="white">

        <p class="text-muted">Donor: <?php echo $row['itemDonor']; ?></p>
        <p><?php echo $row['itemDesc']; ?></p>

      <?php if (!is_null($row['itemMedia']) && !($row['itemMedia'] == "")) {?>
        <!-- <div class="row"> -->
      <div class="carousel image-viewer">
        <?php 
            $s = explode("|", $row['itemMedia']);
            $j = sizeof($s);
            
            for ($i=0; $i < $j; $i++) { 
                echo '<div class="carousel-cell image-viewer"><img data-flickity-lazyload="'.$s[$i].'" /></div>';
            }
          ?>
      </div>
      <!-- </div> -->
      <?php } ?>
    </div>
    </div>

    <?php if (strpos($_SESSION['userPerms'], 'A') !== false) { ?>
    <!-- Button trigger modal -->
    <div class="float-sm-right d-flex justify-content-end">
      <button id="modalActivate" type="button" class="btn btn-outline-warning waves-effect" data-toggle="modal" data-target="#editEntry">Edit</button>
      <button id="modalActivate" type="button" class="btn btn-outline-danger waves-effect" data-toggle="modal" data-target="#deleteEntry">Delete</button>
    </div>
    <?php } ?>

    <!-- Edit modal -->
    <div class="modal fade" id="editEntry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content blue-grey darken-1">
          <div class="modal-header text-center">
            <h4 class="modal-title w-100 font-weight-bold white-text">Edit item</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span class="white-text" aria-hidden="true">&times;</span>
            </button>
          </div>

          <form action="" method="post">
            <div class="modal-body mx-3">
              <div class="md-form mb-4">
                <?php
                echo '<input type="text" id="itemTitle" name="Title" class="form-control white-text validate" value="'.$row['itemTitle'].'">';
                ?>
                <label class="white-text" data-error="wrong" data-success="right" for="itemTitle">Title</label>
              </div>
              
              <div class="md-form mb-4">
                <?php
                echo '<input type="text" id="itemDonor" name="Donor" class="form-control white-text validate" value="'.$row['itemDonor'].'">';
                ?>
                <label class="white-text" data-error="wrong" data-success="right" for="itemDonor">Donor</label>
              </div>
              
              <div class="md-form mb-4">
                <?php
                echo '<input type="text" id="itemDesc" name="Desc" class="form-control white-text validate" value="'.$row['itemDesc'].'">';
                ?>
                <label class="white-text" data-error="wrong" data-success="right" for="itemDesc">Description</label>
              </div>
              
              <div class="md-form mb-4">
                <label class="white-text">Accession date</label>
                <?php
                echo '<input type="date" name="ADate" class="form-control white-text" value="'.$row['itemADate'].'">';
                ?>
              </div>
              
              <div class="md-form mb-4">
                <label class="white-text">Loan date</label>
                <?php
                echo '<input type="date" name="LDate" class="form-control white-text" value="'.$row['itemLDate'].'">';
                ?>
              </div>
              
              <div class="md-form mb-4">
                <?php
                echo '<input type="number" min="1" max="999" id="itemAmount" name="Amount" class="form-control white-text validate" value="'.$row['itemAmount'].'">';
                ?>
                <label class="white-text" data-error="wrong" data-success="right" for="itemAmount">Number of items</label>
              </div>

              <div class="mb-4 switch custom-control-inline">
					      <label class="white-text">
                  <?php if ($row['itemD']) {
                    echo '<input type="checkbox" name="D" checked>';
                  } else {
                    echo '<input type="checkbox" name="D">';
                  }
                  ?>
                  <span class="lever"></span> Deaccessioned
            		</label>
            	</div>

            </div>
            <div class="modal-footer d-flex justify-content-center">
              <?php
                echo '<input type="hidden" name="id" value="'.$_GET['item'].'">';
              ?>
              <input type="submit" class="btn btn-purple" name="updateEntry-submit" value="Update">
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Edit modal -->

    <!-- Delete modal -->
    <div class="modal fade top" id="deleteEntry" tabindex="-1" role="dialog" aria-labelledby="exampleModalPreviewLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-frame modal-top" role="document">
        <div class="modal-content blue-grey darken-1">
          <div class="modal-body white-text">
            <h2>Are you sure you want to delete this entry?</h2>
          </div>
          <div class="modal-footer">
            <form action="../includes/entry.inc.php" method="post">
              <?php
                echo '<input type="hidden" name="id" value="'.$_GET['item'].'">';
                echo '<input type="hidden" name="AID" value="'.$row['itemAID'].'">';
              ?>
              <input type="submit" class="btn btn-danger" value="Yes" name="deleteEntry-submit">
            </form>
            <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete modal -->

  <?php }?>
  </div>

</main>
<!--Main layout-->

    <!-- End -->
  <?php require '../pages/utils/D_scripts.php'; ?>

</body>
</html>
