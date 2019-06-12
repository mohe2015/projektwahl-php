<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$teachers = Teachers::all();
?>

<h1>Lehrer</h1>

<a href="/teachers/new.php" class="button">Neuer Lehrer<a>
<a href="/teachers/import.php" class="button">Lehrer importieren<a>
<a href="/teachers/generate_passwords.php" class="button">Passwortliste generieren</a>

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
              <a href="/teachers/edit.php?<?php echo $teacher->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/teachers/delete.php?<?php echo $teacher->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
