<?php
require_once '../head.php';
$teachers = Users::all();
?>

<h1>Lehrer</h1>

<a href="/teacher/new.php" class="button">Neuer Lehrer<a>

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
              <a href="/project/edit.php?<?php echo $teacher->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/project/delete.php?<?php echo $teacher->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
