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
$allowed_users = array("admin", "student", "teacher");
require_once __DIR__ . '/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'):
  session_regenerate_id(true);
  array_pop($_SESSION['users']);
  header("Location: $ROOT/");
  die();
else: ?>
<!doctype html>
<html lang="de">
  <head>
    <?php require __DIR__ . '/head.php' ?>
  </head>
  <body class="bg-dark text-white">
    <?php require __DIR__ . '/nav.php' ?>

    <div class="container container-small">

      <form method="post">
        Möchtest Du dich wirklich abmelden?
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
        <button class="btn btn-primary" type="submit" class="btn btn-primary">Ja</button>
      </form>
    </div>
  <?php require __DIR__ . '/footer.php' ?>
</body>
</html>

<?php endif; ?>