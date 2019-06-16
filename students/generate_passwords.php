<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$timers = new Timers();
$timers->startTimer('all_students');
$students = Students::all();
$timers->endTimer('all_students');

$grouped_students = array();
foreach ($students as $student) {
    $grouped_students[$student->class][] = $student;
}
?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <h1>Passwörter</h1>

  <?php
  $timers->startTimer('generate_passwords');
  $db->beginTransaction();
  foreach ($grouped_students as $class_name => $class) :?>
    <h1><?php echo $class_name ?></h1>
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
          <?php foreach ($class as $student) :?>
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
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endforeach;
  $db->commit();
  $timers->endTimer('generate_passwords');
  header('Server-Timing: ' . $timers->getTimers());
  ?>

<?php else: ?>
  <br />
  <form method="post">
    Möchtest Du wirklich die Passwörter generieren?
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
    <button type="submit">Ja</button>
  </form>
<?php endif; ?>
