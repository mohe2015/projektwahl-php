<?php
require_once '../head.php';
$students = Students::all();
?>

<h1>Schüler, die noch nicht gewählt haben</h1>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student) :?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) ?></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
