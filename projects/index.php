<?php
require_once '../head.php';
$projects = Projects::all();
?>

<h1>Projekte</h1>

<a href="/project/new.php" class="button">Neues Projekt<a>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Vorraussichtliche Größe</th>
          <th scope="col">Aktionen</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($projects as $project) :?>
          <tr>
            <td><?php echo htmlspecialchars($project->title) ?></td>
            <td>unbekannt</td>
            <td>
              <a href="/project/edit.php?<?php echo $project->id ?>"><i class="fas fa-pen"></i></a>
              <a href="/project/delete.php?<?php echo $project->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
