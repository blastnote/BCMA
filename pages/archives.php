<?php 
require 'utils/docStart.php';

if (strpos($_SESSION['userPerms'], 'a') === false) {
  header("Location: ../index.php?perm=archives");
  exit();
}

include_once '../includes/dbhA.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php require 'utils/D_head.php'; ?>
</head>

<body>
  <?php require "utils/nav.php"; ?>

  <!-- Main body -->
  <main class="elegant-color min-vh-100 pt-5 pb-5">

    <!-- <div class="container no-gutters mt-4"> -->
      <!-- Search options -->
      <div class="refine white-text pt-4 mb-2 ml-4 mt-1">
        <!-- Relative radio -->
        <?php
        // <div class="form-check form-check-inline">
        //   <input type="radio" class="form-check-input" id="relativeSearch" name="relativeSearch" checked>
        //   <label class="form-check-label" for="relativeSearch">Relative</label>
        // </div>

        // <!-- Recent radio -->
        // <div class="form-check form-check-inline">
        //   <input type="radio" class="form-check-input" id="recentSearch" name="relativeSearch">
        //   <label class="form-check-label" for="recentSearch">Recent</label>
        // </div>

        // <!-- A-Z radio -->
        // <div class="form-check form-check-inline">
        //   <input type="radio" class="form-check-input" id="AZSearch" name="relativeSearch">
        //   <label class="form-check-label" for="AZSearch">A-Z</label>
        // </div>
        ?>
        <?php $d = 1; if (strpos($_SESSION['userPerms'], 'd') !== false) { ?>
        <!-- Deaccession switch -->
        <!-- <div class="switch custom-control-inline">
          <label>
            <input type="checkbox" name="">
            <span class="lever"></span> Deaccessioned items
          </label>
        </div> -->
        <?php } ?>
      </div>

      <div class="grid ml-4">
        <?php
        if (isset($_GET['search']) && $_GET['search'] !== false && !is_null($_GET['search'])) {
          $t = str_replace("+"," ",$_GET['search']);
          $sql = "SELECT * FROM active WHERE (itemAID LIKE '%".$t."%' OR itemTitle LIKE '%".$t."%' OR itemDesc LIKE '%".$t."%' OR itemDonor LIKE '%".$t."%')";
        } else {
          $sql = "SELECT * FROM active";
        }
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {  ?>
        <!-- Card start -->
        <div class="grid-item card card-cascade mb-2">

          <!-- Card image -->
          <div class="view view-cascade overlay">
            <?php 
            if ($row['itemMedia'] !== "") {
              $s = explode("|", $row['itemMedia']);
              if (file_exists($s[0])) {
                echo '<img class="card-img-top" src="'.$s[0].'" alt="Card image cap">';
              } else { echo '<img class="card-img-top" src="/img/noimage.png" alt="Card image cap">'; }
              
            } else {
              echo '<img class="card-img-top" src="/img/noimage.png" alt="Card image cap">';
            }
            ?>
            
            <?php echo '<a href="entry.php?item='.$row['itemID'].'">'; ?>
              <div class="mask rgba-white-slight"></div>
            </a>


            <!-- Card content -->
            <div class="card-body card-body-cascade stylish-color text-left">
              <!-- Title -->
              <?php echo '<h4 class="card-title white-text"><strong>'.$row['itemTitle'].'</strong></h4>'; ?>
              <hr class="blue-grey lighten-2">
              <?php 
              if ($row['itemD']) {
                echo '<div class="chip secondary-color-dark white-text">Deaccessioned</div>';
              }
                ?>

              <!-- Text -->
              <?php echo '<p class="card-text white-text">'.$row['itemDesc'].'</p>'; ?>

              <?php if (!is_null($row['itemTags']) && !($row['itemTags'] === '')) { 
              $s = explode("|", $row['itemTags']);
              echo '<hr class="blue-grey lighten-2">';
              $j = sizeof($s);
              $numOfTags = 3;
              for ($i=0; $i < (($j >= $numOfTags)? $numOfTags : $j); $i++) { 
                  echo '<div class="chip secondary-color-dark white-text">'.$s[$i].'</div>';
              }
              if (($j - $numOfTags) > 0) {
                echo '<div class="chip secondary-color-dark white-text">+'.($j-$numOfTags).'</div>';
              }

            } ?>
            </div>

            <!-- Card footer -->
            <div class="card-footer stylish-color-dark text-muted text-center">
              <div class="badge secondary-color-dark white-text md-id"><?php echo $row['itemAID']; ?></div>
            </div>

          </div>
        </div>
        <!-- Card end -->
        <?php }} else { echo "Error"; }?>
      </div>
    <!-- </div> -->
  </main>
  <?php require 'utils/D_scripts.php'; ?>

</body>
</html>
