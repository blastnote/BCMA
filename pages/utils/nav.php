<nav class="navbar fixed-top scrolling-navbar navbar-expand-lg navbar-dark unique-color">

  <?php
  if (isset($_POST['search-submit'])) {
    if ($_POST['search'] !== "") {
      $Content = preg_replace("/[^a-zA-Z0-9\s]/", "", $_POST['search']);
      $str = str_replace(" ","+",$Content);
      header("Location: ../pages/archives.php?search=".$str);
      exit();
    }
  }
  ?>

  <!-- Navbar brand -->
  <a class="navbar-brand" href="../index.php">BCM Archives</a>

  <!-- Leftside nav -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">

      <?php if (strpos($_SESSION['userPerms'], 'a') !== false) { ?>
        <li class="nav-item">
          <a class="nav-link" href="../pages/archives.php">Archives</a>
        </li>
      <?php } ?>

        <?php /*
      <?php if (strpos($_SESSION['userPerms'], 'e') !== false) { ?>
        <li class="nav-item">
          <a class="nav-link" href="#">Exhibits</a>
        </li>
      <?php } ?>

      <?php if (strpos($_SESSION['userPerms'], 'r') !== false) { ?>
        <li class="nav-item">
          <a class="nav-link" href="#">Research</a>
        </li>
      <?php } ?>
        */?>

      <?php if (strpos($_SESSION['userPerms'], 'A') !== false) { ?>
        <li class="nav-item">
          <a class="nav-link" href="../pages/newEntry.php">New entry</a>
        </li>
      <?php } ?>
    </ul>

    <!-- Rightside nav -->
    <ul class="navbar-nav ml-auto nav-flex-icons">
      <li>
        <form class="form-inline" action="" method="POST">
          <div class="md-form my-0">
          <button class="mr-sm-2 searchbtn" type="submit" name="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
            <?php 
            if (isset($_GET['search'])) {
              if ($_GET['search'] !== false && !is_null($_GET['search'])) {
                $t = str_replace("+"," ",$_GET['search']);
                echo '<input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search" value="'.$t.'">';
              }
            } else {
              echo '<input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search" name="search">';
            }
            ?>
          </div>
        </form>
      </li>
    </ul>
      
    <div class="nav-item avatar dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src="../img/account.png" class="rounded-circle z-depth-0" alt="avatar image">
      </a>
      <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-55">
        <a class="dropdown-item" href="../pages/settings.php">Settings</a>
        <a class="dropdown-item" href="../includes/logout.inc.php">Log out</a>
      </div>
    </div>
    
  </div>
</nav>