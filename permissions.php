<?php
if (isset($allowed_users) && $allowed_users == false) { // array empty but set
  // Do nothing
} else if (!in_array($_SESSION['type'], $allowed_users)) {
  die("Keine Berechtigung!");
}
?>
