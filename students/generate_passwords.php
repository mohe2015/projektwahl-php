<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$timers = new Timers();
$timers->startTimer('all_students');
$students = Students::allWithoutPasswords();
$timers->endTimer('all_students');

$grouped_students = array();
foreach ($students as $student) {
    $grouped_students[$student->class][] = $student;
}
?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <h1 class="print-display-none">Passwörter</h1>
  <p class="print-display-none">Bitte 4 Seiten pro Blatt drucken, um die Umwelt zu schonen.</p>
  <p class="print-display-none">Die Listen sollten in Streifen geschnitten werden, um zu verhindern, dass Passwörter in falsche Hände gelangen.</p>
  <?php
  $timers->startTimer('generate_passwords');
  $db->beginTransaction();
  foreach ($grouped_students as $class_name => $class) :?>
    <h1 class="print-display-none"><?php echo $class_name ?></h1>
    <div class="monospace">
      <table>
        <thead>
          <tr>
            <th scope="col">Name (<?php echo $class_name ?>)</th>
            <th scope="col">Passwort</th>
            <th scope="col">Website</th
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
              <td class="min"><?php echo htmlspecialchars($password) ?></td>
              <td class="min"><?php echo $_SERVER['HTTP_HOST'] ?></td>
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
