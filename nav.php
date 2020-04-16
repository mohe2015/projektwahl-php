<nav class="navbar navbar-expand-lg navbar-dark bg-dark d-print-none">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">Projektwahl</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
        <li class="nav-item<?php active_exact($ROOT . "/") ?>"><a class="nav-link" href="<?php echo $ROOT ?>/"><i class="fas fa-home"></i><span class="hidden-small"> Startseite</span></a></li>
<?php
// hide links if user has no permission to access them
if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin", "teacher"))): ?>
        <li class="nav-item<?php active($ROOT . "/project") ?>"><a class="nav-link" href="<?php echo $ROOT ?>/projects"><i class="fas fa-users"></i><span class="hidden-small"> Projekte</span></a></li>
<?php endif; ?>
<?php if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin"))): ?>
        <li class="nav-item<?php active($ROOT . "/teacher") ?>"><a class="nav-link" href="<?php echo $ROOT ?>/teachers"><i class="fas fa-chalkboard-teacher"></i><span class="hidden-small"> Lehrer</span></a></li>
<?php endif; ?>
<?php if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin", "teacher"))): ?>
        <li class="nav-item <?php active($ROOT . "/student") ?>"><a class="nav-link" href="<?php echo $ROOT ?>/students"><i class="fas fa-user"></i><span class="hidden-small"> Schüler</span></a></li>
<?php endif; ?>
<?php if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("student"))): ?>
        <li class="nav-item <?php active($ROOT . "/election.php") ?>"><a class="nav-link" href="<?php echo $ROOT ?>/election.php"><i class="fas fa-poll"></i><span class="hidden-small"> Wahl</span></a></li>
<?php endif; ?>



<?php
if (end($_SESSION['users']) && in_array(end($_SESSION['users'])->type, array("admin"))):
?>
  <li class="nav-item float-right"><a class="nav-link" href="<?php echo $ROOT ?>/update-election-state.php"><i class="fas fa-ban"></i><span class="hidden-small"> Wahl <?php echo $settings->election_running ? "beenden" : "starten" ?></span></a></li>
<?php endif; ?>

<?php if (isset(end($_SESSION['users'])->type)): ?>
      <li class="nav-item float-right"><a class="nav-link" href="<?php echo $ROOT ?>/logout.php"><i class="fas fa-sign-out-alt"></i><span class="hidden-small"> <?php echo end($_SESSION['users'])->name ?> abmelden</span></a></li>
      <li class="nav-item float-right"><a class="nav-link" href="<?php echo $ROOT ?>/update-password.php"><i class="fas fa-key"></i><span class="hidden-small"> Passwort ändern</span></a></li>
<?php endif; ?>
      </ul>

    </div>
   </div>
</nav>
