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
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$users = Teachers::allWithoutPasswords();
?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <h1 class="print-display-none">Passwörter</h1>
  <p class="print-display-none">Bitte 4 Seiten pro Blatt drucken, um die Umwelt zu schonen.</p>
  <p class="print-display-none">Die Listen sollten in Streifen geschnitten werden, um zu verhindern, dass Passwörter in falsche Hände gelangen.</p>
  <?php
  $db->beginTransaction();
  ?>
  <div class="monospace">
    <table>
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Passwort</th>
          <th scope="col">Website</th
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) :?>
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
  <?php
  $db->commit();
  ?>

<?php else: ?>
  <br />
  <form method="post">
    Möchtest Du wirklich die Passwörter generieren?
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
    <button type="submit">Ja</button>
  </form>
<?php endif; ?>
