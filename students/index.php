<?php
require_once '../head.php';
$students = Students::all();
?>

<h1>Schüler</h1>

<a href="/student/new.php" class="button">Neuer Schüler<a>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student) :?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) ?></td>
            <td>
              <a href="/student/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/student/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
