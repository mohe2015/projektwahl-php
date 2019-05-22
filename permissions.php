<?php
if (!in_array($_SESSION['type'], $allowed_users)) {
  die("Keine Berechtigung!");
}
?>
