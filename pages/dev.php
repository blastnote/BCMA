<?php require 'utils/docStart.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php require 'utils/D_head.php'; ?>
</head>

<body>
<?php require 'utils/nav.php'; ?>
        
<!--Main layout-->
<main class="elegant-color min-vh-100 pt-5">
  <div class="container-fluid white-text pt-5 pb-5">

    <h1>Development Notes</h1>
    <hr class="white">

    <h2>About Project</h2>
    <p>This intranet site was built for Behringer-Crawford Museum and was started by Nick Brinthaupt (intern) on January, 2020. The purpose for this site was to provide a way to do online collection logging anywhere on the internal network.<span class="warning-color black-text"> THIS IS NOT A FINISHED PRODUCT.</span> Due to restrictions on time, the turn around period for this project has been really short (about 4 months). Not everything is done in the most optimized way. That being said, there is are different lists below for different things that need to be added based on complexity and time. This project has been built using git. More technical info is provided on server machine, but this is the base overview. Please update this page in future development and change the number after every completed item following the "Major . Minor . Patch" format.</p>

    <h2>Major To-Do</h2>
    <ul>
        <li>!!! Create symlink to external drive for mediapool with registry on start-up (Low on server storage) !!!</li>
        <li>Add UPS system for safety</li>
        <li>Create backup script for off-site/ cloud storage</li>
        <li>Research collection bins and page</li>
        <li>Exhibit planning page with item links</li>
        <li>Adjust css colors for potential skinning (requires site comb through)</li>
        <li>Recent items viewed carousel on home page</li>
        <li>Items on loan carousel organized by date</li>
        <li>Dynamic editing/ viewing of item on single page</li>
        <li>Reorganize PHP server for expansion</li>
        <li>Make (more) mobile friendly</li>
        <li>Rework site permissions</li>
        <li>Multi entry adding</li>
        <li>Multi file upload</li>
    </ul>

    <h2>Minor To-Do</h2>
    <ul>
        <li>File upload</li>
        <li>Entry editing/ deleting</li>
        <li>Page limit update (jqueary ajax)</li>
        <li>Settings rework</li>
        <li>Display deaccessioned items</li>
    </ul>

  </div>
</main>
<!--Main layout-->

<!-- End -->
<?php require 'utils/D_scripts.php'; ?>

</body>
</html>