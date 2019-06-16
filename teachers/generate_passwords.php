<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

$timers = new Timers();
$timers->startTimer('all_users');
$users = Teachers::allWithoutPasswords();
$timers->endTimer('all_users');

?>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
  <h1 class="print-display-none">Passwörter</h1>
  <p class="print-display-none">Bitte 4 Seiten pro Blatt drucken, um die Umwelt zu schonen.</p>
  <p class="print-display-none">Die Listen sollten in Streifen geschnitten werden, um zu verhindern, dass Passwörter in falsche Hände gelangen.</p>
  <?php
  $timers->startTimer('generate_passwords');
  $db->beginTransaction();
  ?>
  <div class="monospace">
    <table>
      <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Passwort</th>
          <th scope="col">Website</th
        </tr>
      </thead>
      <tbody>
        <?php foreach ($users as $user) :?>
          <?php
          $password = bin2hex(random_bytes(5));
          $user->password = password_hash($password, PASSWORD_DEFAULT, $options);
          $user->save();
          ?>
          <tr>
            <td><?php echo htmlspecialchars($user->name) ?></td>
            <td class="min"><?php echo htmlspecialchars($password) ?></td>
            <td class="min"><?php echo htmlspecialchars($_SERVER['HTTP_HOST']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php
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
