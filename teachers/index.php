<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$teachers = Teachers::all();
?>

<h1>Lehrer</h1>

<a href="/teachers/new.php" class="button">Neuer Lehrer</a>
<a href="/teachers/import.php" class="button">Lehrer importieren</a>
<form class="inline-block" method="POST" action="generate_passwords.php">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <button type="submit" href="/teachers/generate_passwords.php" class="button">Passwortliste generieren</button>
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
              <a href="/teachers/edit.php?<?php echo $teacher->id ?>"><i class="fas fa-pen"></i></a>
              <form class="inline-block" method="post" action="edit.php?<?php echo $teacher->id ?>">
                <input type="hidden" name="password" value="">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                <button class="a" type="submit"><i class="fas fa-key"></i></button>
              </form>
              <a href="/teachers/delete.php?<?php echo $teacher->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
