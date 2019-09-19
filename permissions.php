<?php
if (isset($allowed_users) && $allowed_users == false) { // array empty but set
  // Do nothing
} else if (!isset(end($_SESSION['users'])->name)) {
  header("Location: $ROOT/login.php");
  die("Nicht angemeldet!");
} else if (!in_array(end($_SESSION['users'])->type, $allowed_users)) {
  http_response_code(403);
  die("Keine Berechtigung!");
}
?>
