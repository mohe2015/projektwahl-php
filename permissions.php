<?php
if (isset($allowed_users) && $allowed_users == false) { // array empty but set
  // Do nothing
} else if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
} else if (!in_array($_SESSION['type'], $allowed_users)) {
  die("Keine Berechtigung!");
}
?>
