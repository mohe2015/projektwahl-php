<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $timers = new Timers();
  $timers->startTimer('import');
  $db->beginTransaction();
  try {
    if (($handle = fopen($_FILES['csv-file']['tmp_name'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($num != 1) {
              echo "nur eine Spalte erlaubt!";
              break;
            }
            $teacher = new Teacher();
            $teacher->name = $data[0];
            $teacher->password = "none"; // FIXME
            $teacher->save();
        }
        fclose($handle);
    } else {
      echo "Keine Datei ausgewählt!";
    }
  } catch (Exception $e) {
    echo $e->getMessage();
  }
  $db->commit();
  $timers->endTimer('import');
  header('Server-Timing: ' . $timers->getTimers());
}
?>
<form enctype="multipart/form-data" method="POST">
  <div class="form-group">
    <label class="col">CSV-Datei:</label>
    <input class="col" name="csv-file" type="file" />
  </div>

    <div class="form-group">
      <button class="w-100" type="submit">Lehrer importieren</button>
    </div>
</form>
