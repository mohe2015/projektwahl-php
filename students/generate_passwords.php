<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';
if (!isset($_SESSION['name'])) {
  header("Location: /login.php");
  die("not logged in");
}
$timers = new Timers();
$timers->startTimer('all_students');
$students = Students::all();
$timers->endTimer('all_students');
?>

<h1>Passwörter</h1>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Klasse</th>
          <th scope="col">Passwort</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $timers->startTimer('generate_passwords');
        $db->beginTransaction();
        foreach ($students as $student) :?>
          <?php
            $password = bin2hex(random_bytes(5));
            $student->password = password_hash($password, PASSWORD_DEFAULT, $options);
            $student->save();
          ?>
          <tr>
            <td><?php echo htmlspecialchars($student->name) ?></td>
            <td><?php echo htmlspecialchars($student->class) ?></td>
            <td><?php echo htmlspecialchars($password) ?></td>
          </tr>
        <?php endforeach;
        $db->commit();
        $timers->endTimer('generate_passwords');
        header('Server-Timing: ' . $timers->getTimers());
        ?>
      </tbody>
  </table>
</div>
