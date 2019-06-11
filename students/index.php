<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$students = Students::all();
?>

<h1>Schüler</h1>

<a href="/students/new.php" class="button">Neuer Schüler</a>
<a href="/students/import.php" class="button">Schüler importieren</a>
<a href="/students/generate_passwords.php" class="button">Passwortliste generieren</a>
<a href="/students/not_voted.php" class="button">Schüler ohne gewählte Projekte</a>
<a href="/students/calculate.php" class="button">Projektzuordnungen berechnen</a>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student) :?>
          <tr>
            <td><a href="/students/view.php?<?php echo $student->id ?>"><?php echo htmlspecialchars($student->name) ?></a></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
            <td>
              <a href="/students/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/students/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
