<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$timers = new Timers();
$timers->startTimer('all_teachers');
$teachers = Teachers::all();
$timers->endTimer('all_teachers');
?>

<h1>Passwörter</h1>

<div class="responsive">
  <table>
    <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Passwort</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $timers->startTimer('generate_passwords');
        $db->beginTransaction();
        foreach ($teachers as $teacher) :?>
          <?php
            $password = bin2hex(random_bytes(5));
            $teacher->password = password_hash($password, PASSWORD_DEFAULT, $options);
            $teacher->save();
          ?>
          <tr>
            <td><?php echo htmlspecialchars($teacher->name) ?></td>
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
