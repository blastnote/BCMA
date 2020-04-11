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
<main class="elegant-color min-vh-100 pt-5">

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
          <div class="card-footer stylish-color-dark text-light text-center">
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
  <?php } else { echo "sql error"; }?>
  </div>

</main>
<!--Main layout-->

    <!-- End -->
  <?php require '../pages/utils/D_scripts.php'; ?>

</body>
</html>
