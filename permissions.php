<?php
if (isset($allowed_users) && $allowed_users == false) { // array empty but set
  // Do nothing
} else if (!isset(end($_SESSION['users'])->name)) {
  header("Location: /login.php");
  die("not logged in");
} else if (!in_array(end($_SESSION['users'])->type, $allowed_users)) {
  die("Keine Berechtigung!");
}
?>
