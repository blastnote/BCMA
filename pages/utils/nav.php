<nav class="navbar fixed-top scrolling-navbar navbar-expand-lg navbar-dark unique-color">

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

      <?php if (strpos($_SESSION['userPerms'], 'A') !== false) { ?>
      <li class="nav-item">
        <a class="nav-link" href="../pages/newEntry.php">New entry</a>
      </li>
      <?php } ?>
    </ul>

    <!-- Rightside nav -->
    <ul class="navbar-nav ml-auto nav-flex-icons">
      <li>
        <form class="form-inline">
          <div class="md-form my-0">
            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          </div>
        </form>
      </li>
      <li class="nav-item avatar dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <img src="../img/account.png" class="rounded-circle z-depth-0"
            alt="avatar image">
        </a>
        <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary"
          aria-labelledby="navbarDropdownMenuLink-55">
          <a class="dropdown-item" href="../pages/settings.php">Settings</a>
          <a class="dropdown-item" href="../includes/logout.inc.php">Log out</a>
        </div>
      </li>
    </ul>
  </div>
</nav>