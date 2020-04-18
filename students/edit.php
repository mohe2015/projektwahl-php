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
$allowed_users = array("admin", "teacher"); // TODO teachers only absent
require_once __DIR__ . '/../header.php';

$student = Students::find($_SERVER['QUERY_STRING']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $_POST['away'] = !empty($_POST['away']);
    $_POST['password'] = empty($_POST['password']) ? NULL : $_POST['password'];
    $student->update($_POST);
    $student->save();
  } catch (Exception $e) {
    die(htmlspecialchars($e->getMessage()));
  }
  header("Location: $ROOT/students");
  die();
}
?>
<!doctype html>
<html lang="de">
  <head>
    <?php require __DIR__ . '/../head.php' ?>
  </head>
  <body class="bg-dark text-white">
    <?php require __DIR__ . '/../nav.php' ?>

    <div class="container">

<h1 class="text-center">Schüler ändern</h1>
<?php
require_once __DIR__ . '/form.php';
?>

</div>
<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
