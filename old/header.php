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
$ROOT = substr(dirname(__FILE__), strlen($_SERVER["DOCUMENT_ROOT"]));

// show error message if exception is not caught
function myException($exception) {
  http_response_code(500);
  echo '<div class="alert alert-danger" role="alert">Interner Fehler: ' . $exception->getMessage() . '<br />Eventuell musst du erst <a href="$ROOT/install.php" class="alert-link">installieren</a>.</div>';
  die();
}
//set_exception_handler('myException');

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

require_once __DIR__ . '/model/user.php';
session_start();
require_once __DIR__ . '/model/project.php';
require_once __DIR__ . '/model/teacher.php';
require_once __DIR__ . '/model/student.php';
require_once __DIR__ . '/model/choice.php';
require_once __DIR__ . '/model/settings.php';
require_once __DIR__ . '/permissions.php';

require_once __DIR__ . '/form-controls/text_input.php';
require_once __DIR__ . '/form-controls/number_input.php';
require_once __DIR__ . '/form-controls/range_input.php';

// SECURITY: checks whether the CSRF csrf_token is valid https://en.wikipedia.org/wiki/Cross-site_request_forgery
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('<div class="alert alert-danger" role="alert">CSRF csrf_token not valid</div>');
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
  die('<div class="alert alert-danger" role="alert">' . $e . '</div>');
}

try {
  $settings = Settings::get();
} catch (PDOException $e) {
  echo('<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>');
}

if (0 !== count($_SESSION['users']) && $_SESSION['users'][0]->type !== 'admin' && $_SERVER['REQUEST_URI'] !== "/update-password.php" && $_SERVER['REQUEST_URI'] !== "/zxcvbn.php" && $_SERVER['REQUEST_URI'] !== "/logout.php" && !end($_SESSION['users'])->password_changed) {
  header("Location: $ROOT/update-password.php");
}

// used to add the active class to the current tab
function active($path) {
  echo startsWith($_SERVER["REQUEST_URI"], $path) ? ' active' : '';
}

// used to add the active class to the current tab
function active_exact($path) {
  echo $_SERVER["REQUEST_URI"] === $path ? ' active' : '';
}
?>
