<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$students = Students::all();
?>

<h1>Schüler</h1>

<a href="/students/new.php" class="button">Neuer Schüler</a>
<a href="/students/import.php" class="button">Schüler importieren</a>
<form class="inline-block" method="POST" action="generate_passwords.php">
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
  <button type="submit" href="/teachers/generate_passwords.php" class="button">Passwortliste generieren</button>
</form>
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
              <form class="inline-block" method="post" action="edit.php?<?php echo $student->id ?>">
                <input type="hidden" name="away" value="<?php echo $student->away ? "" : "checked" ?>">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button class="a" type="submit"><i class="fas <?php echo $student->away ? "fa-user-slash" : "fa-user" ?>"></i></button>
              </form>
              <form class="inline-block" method="post" action="edit.php?<?php echo $student->id ?>">
                <input type="hidden" name="password" value="">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                <button class="a" type="submit"><i class="fas fa-key"></i></button>
              </form>
              <a href="/students/sudo.php?<?php echo $student->id ?>"><i class="fas fa-sign-in-alt"></i></a>
              <a href="/students/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
  </table>
</div>
