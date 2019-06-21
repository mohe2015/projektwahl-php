<?php
// show error message if exception is not caught
function myException($exception) {
  http_response_code(500);
  echo "<b>Interner Fehler: </b> " . $exception->getMessage();
  echo '<br />Eventuell musst du erst <a href="/install.php">installieren</a>';
  die();
}
set_exception_handler('myException');

apcu_clear_cache(); // TODO FIXME just for developing so that code changes update

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

require_once __DIR__ . '/user.php';
session_start();
require_once __DIR__ . '/project.php';
require_once __DIR__ . '/teacher.php';
require_once __DIR__ . '/student.php';
require_once __DIR__ . '/choice.php';
require_once __DIR__ . '/settings.php';
require_once __DIR__ . '/timers.php';
require_once __DIR__ . '/permissions.php';

// SECURITY: checks whether the CSRF token is valid https://en.wikipedia.org/wiki/Cross-site_request_forgery
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['token'], $_POST['token'])) {
    die("CSRF token not valid");
  }
}
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

// connect to database
require_once 'config.php';
try {
    $db = new PDO($database['url'], $database['username'], $database['password'], array(
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ));
} catch (PDOException $e) {
    print "Error!: " . $e . "<br/>";
    die();
}

if (0 !== count($_SESSION['users']) && $_SERVER['REQUEST_URI'] !== "/update-password.php" && $_SERVER['REQUEST_URI'] !== "/logout.php" && !end($_SESSION['users'])->password_changed) {
  header("Location: /update-password.php");
}
?>
