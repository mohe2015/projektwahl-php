<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
require_once __DIR__ . '/header.php';

// used to add the active class to the current tab
function active($path) {
  echo startsWith($_SERVER["REQUEST_URI"], $path) ? ' active' : '';
}

// used to add the active class to the current tab
function active_exact($path) {
  echo $_SERVER["REQUEST_URI"] === $path ? ' active' : '';
}
?><!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <script src="/bootstrap/dist/js/bootstrap.bundle.js"></script>
    <script src="<?php echo $ROOT ?>/base.js"></script>
    <script src="<?php echo $ROOT ?>/scroll.js"></script>
    <title>Projektwahl</title>
  </head>
  <body class="bg-dark text-white">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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

    <div class="container">
