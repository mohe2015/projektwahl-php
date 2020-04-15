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

$teachers = Teachers::all();
?>

<h1>Lehrer</h1>

<a role="button" class="mb-1" href="<?php echo $ROOT ?>/teachers/new.php">Neuer Lehrer</a>
<a role="button" class="mb-1" href="<?php echo $ROOT ?>/teachers/import.php">Lehrer importieren</a>

<form class="d-inline" method="POST" action="generate_passwords.php">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit" class="mb-1" href="<?php echo $ROOT ?>/teachers/generate_passwords.php">Passwortliste generieren</button>
</form>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teachers as $teacher) :?>
          <tr>
            <td><?php echo htmlspecialchars($teacher->name) ?></td>
            <td>
              <a role="button" href="<?php echo $ROOT ?>/teachers/edit.php?<?php echo $teacher->id ?>"><i class="fas fa-pen"></i></a>
              <form class="d-inline" method="post" action="edit.php?<?php echo $teacher->id ?>">
                <input type="hidden" name="password" value="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button type="submit"><i class="fas fa-key"></i></button>
              </form>
              <a role="button" href="<?php echo $ROOT ?>/teachers/delete.php?<?php echo $teacher->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
