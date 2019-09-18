<?php
require_once __DIR__ . '/header.php';

// used to add the active class to the current tab
function active($path) {
  echo startsWith($_SERVER["REQUEST_URI"], $path) ? 'class="active"' : '';
}

// used to add the active class to the current tab
function active_exact($path) {
  echo $_SERVER["REQUEST_URI"] === $path ? 'class="active"' : '';
}
?><!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="<?php echo $ROOT ?>/base.js"></script>
    <link rel="stylesheet" href="<?php echo $ROOT ?>/dialog-polyfill.css">
    <link rel="stylesheet" href="<?php echo $ROOT ?>/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.2/css/all.min.css" integrity="sha256-zmfNZmXoNWBMemUOo1XUGFfc0ihGGLYdgtJS3KCr/l0=" crossorigin="anonymous" />
    <!-- This is a polyfill to support the old firefox browser in the school. -->
    <script src="<?php echo $ROOT ?>/polyfill.js"></script>
    <script src="<?php echo $ROOT ?>/dialog-polyfill.js"></script>
    <script src="<?php echo $ROOT ?>/scroll.js"></script>
    <title>Projektwahl</title>
  </head>
  <body>
    <nav>
      <ul>
         <li><a <?php active_exact($ROOT . "/") ?> href="<?php echo $ROOT ?>/"><i class="fas fa-home"></i><span class="hidden-small"> Startseite</span></a></li>
<?php
// hide links if user has no permission to access them
if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin", "teacher"))): ?>
         <li><a <?php active($ROOT . "/project") ?> href="<?php echo $ROOT ?>/projects"><i class="fas fa-users"></i><span class="hidden-small"> Projekte</span></a></li>
<?php endif; ?>
<?php if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin"))): ?>
         <li><a <?php active($ROOT . "/teacher") ?> href="<?php echo $ROOT ?>/teachers"><i class="fas fa-chalkboard-teacher"></i><span class="hidden-small"> Lehrer</span></a></li>
<?php endif; ?>
<?php if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin", "teacher"))): ?>
         <li><a <?php active($ROOT . "/student") ?> href="<?php echo $ROOT ?>/students"><i class="fas fa-user"></i><span class="hidden-small"> Schüler</span></a></li>
<?php endif; ?>
<?php if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("student"))): ?>
         <li><a <?php active($ROOT . "/election.php") ?> href="<?php echo $ROOT ?>/election.php"><i class="fas fa-poll"></i><span class="hidden-small"> Wahl</span></a></li>
<?php endif; ?>



<?php
if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin"))):
?>
   <li class="float-right"><a href="<?php echo $ROOT ?>/update-election-state.php"><i class="fas fa-ban"></i><span class="hidden-small"> Wahl <?php echo $settings->election_running ? "beenden" : "starten" ?></span></a></li>
 <?php endif; ?>

<?php if (isset(end($_SESSION['users'])->type)): ?>
       <li class="float-right"><a href="<?php echo $ROOT ?>/logout.php"><i class="fas fa-sign-out-alt"></i><span class="hidden-small"> <?php echo end($_SESSION['users'])->name ?> abmelden</span></a></li>
       <li class="float-right"><a href="<?php echo $ROOT ?>/update-password.php"><i class="fas fa-key"></i><span class="hidden-small"> Passwort ändern</span></a></li>
<?php endif; ?>
       </ul>
    </nav>

    <div class="container">
