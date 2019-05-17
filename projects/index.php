<?php
require_once '../head.php';
$projects = Projects::all();
print_r($projects);
?>

<h1>Projekte</h1>

<a href="/projects/new.php" class="button">Neues Projekt<a>

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
        <tr>
          <td>Mark</td>
          <td>Otto</td>
          <td>@mdo</td>
        </tr>
      </tbody>
  </table>
</div>
