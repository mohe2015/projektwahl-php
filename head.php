<?php
require_once 'header.php';

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function active($path) {
  echo startsWith($_SERVER["REQUEST_URI"], $path) ? 'class="active"' : '';
}

function active_exact($path) {
  echo $_SERVER["REQUEST_URI"] === $path ? 'class="active"' : '';
}

?><!doctype html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/index.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <title>Projektwahl</title>
  </head>
  <body>
    <nav>
      <ul>
         <li><a <?php active_exact("/") ?> href="/"><i class="fas fa-home"></i><span class="hidden-small"> Startseite</span></a></li>
         <li><a <?php active("/project") ?> href="/projects"><i class="fas fa-users"></i><span class="hidden-small"> Projekte</span></a></li>
         <li><a <?php active("/teacher") ?> href="/teachers"><i class="fas fa-chalkboard-teacher"></i><span class="hidden-small"> Lehrer</span></a></li>
         <li><a <?php active("/student") ?> href="/students"><i class="fas fa-user"></i><span class="hidden-small"> Schüler</span></a></li>
         <li class="float-right"><a href="#"><i class="fas fa-ban"></i><span class="hidden-small"> Wahl beenden</span></a></li>
         <li class="float-right"><a href="#"><i class="fas fa-sign-out-alt"></i><span class="hidden-small"> Abmelden</span></a></li>
        </li>
       </ul>
    </nav>

    <div class="container">
