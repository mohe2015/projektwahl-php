<?php
$ROOT = substr(dirname(__FILE__), strlen($_SERVER["DOCUMENT_ROOT"]));

// show error message if exception is not caught
function myException($exception) {
  http_response_code(500);
  echo "<b>Interner Fehler: </b> " . $exception->getMessage();
  echo "<br />Eventuell musst du erst <a href=\"$ROOT/install.php\">installieren</a>";
  die();
}
//set_exception_handler('myException');

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

// SECURITY: checks whether the CSRF csrf_token is valid https://en.wikipedia.org/wiki/Cross-site_request_forgery
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die("CSRF csrf_token not valid");
  }
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = array();
}

// connect to database
require_once __DIR__ . '/config.php';
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

try {
  $settings = Settings::get();
} catch (PDOException $e) {
  echo $e->getMessage();
}

if (0 !== count($_SESSION['users']) && $_SESSION['users'][0]->type !== 'admin' && $_SERVER['REQUEST_URI'] !== "/update-password.php" && $_SERVER['REQUEST_URI'] !== "/logout.php" && !end($_SESSION['users'])->password_changed) {
  header("Location: $ROOT/update-password.php");
}
?>
