<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

// Import students from a .csv file. The file needs to have three columns (name, class, grade) and no header.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $timers = new Timers();
  $timers->startTimer('import');
  $db->beginTransaction();
  try {
    if (($handle = fopen($_FILES['csv-file']['tmp_name'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($num != 3) {
              echo "drei Spalten benötigt (Name, Klasse, Jahrgang)!";
              break;
            }
            $student = new Student();
            $student->name = $data[0];
            $student->class = $data[1];
            $student->grade = $data[2];
            $student->away = false;
            $student->password = "none"; // FIXME
            $student->save();
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
  header("Location: /students");
  die();
}
?>
<form enctype="multipart/form-data" method="POST">
  <div class="form-group">
    <label class="col">CSV-Datei:</label>
    <input class="col" name="csv-file" type="file" />
  </div>

  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />

  <div class="form-group">
    <button class="w-100" type="submit">Schüler importieren</button>
  </div>
</form>
