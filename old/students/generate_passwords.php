<?php
/*
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
$allowed_users = array("admin");
require_once __DIR__ . '/../header.php';

$users = Students::allWithoutPasswords();

$grouped_users = array();
foreach ($users as $user) {
    $grouped_users[$user->class][] = $user;
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

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <h1 class="text-center d-print-none">Passwörter</h1>
  <p class="d-print-none">Bitte 4 Seiten pro Blatt drucken, um die Umwelt zu schonen.</p>
  <p class="d-print-none">Die Listen sollten in Streifen geschnitten werden, um zu verhindern, dass Passwörter in falsche Hände gelangen.</p>
  <?php
  $db->beginTransaction();
  foreach ($grouped_users as $class_name => $class) :?>
    <h1 class="text-center d-print-none"><?php echo $class_name ?></h1>
    <div class="monospace">
      <table class="table table-dark">
        <thead>
          <tr>
            <th scope="col">Name (<?php echo $class_name ?>)</th>
            <th scope="col">Passwort</th>
            <th scope="col">Website</th
          </tr>
        </thead>
        <tbody>
          <?php foreach ($class as $user) :?>
            <?php
            $password = bin2hex(random_bytes(5));
            $user->password = password_hash($password, PASSWORD_DEFAULT, $options);
            $user->save();
            ?>
            <tr>
              <td><?php echo htmlspecialchars($user->name) ?></td>
              <td class="min"><?php echo htmlspecialchars($password) ?></td>
              <td class="min"><?php echo htmlspecialchars($_SERVER['HTTP_HOST']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach;
  $db->commit();
  ?>

<?php else: ?>
  <br />
  <form method="post">
    Möchtest Du wirklich die Passwörter generieren?
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
    <button class="btn btn-primary" type="submit">Ja</button>
  </form>
<?php endif; ?>

</div>
<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
