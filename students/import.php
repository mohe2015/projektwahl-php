<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

// Import students from a .csv file. The file needs to have three columns (name, class, grade) and no header.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $timers = new Timers();
    $timers->startTimer('import');
    $db->beginTransaction();
    if (($handle = fopen($_FILES['csv-file']['tmp_name'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($num != 3) {
              throw new Exception("drei Spalten benötigt (Name, Klasse, Jahrgang)!");
            }
            $student = new Student();
            $student->name = $data[0];
            $student->class = $data[1];
            $student->grade = $data[2];
            $student->away = false;
            $student->password = NULL;
            $student->save();
        }
        fclose($handle);

        $db->commit();
        $timers->endTimer('import');
        header('Server-Timing: ' . $timers->getTimers());
        header("Location: /students");
        die();
    } else {
      throw new Exception("Keine Datei ausgewählt!");
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    $db->rollback();
    $timers->endTimer('import');
    header('Server-Timing: ' . $timers->getTimers());
  }
}
?>
<form enctype="multipart/form-data" method="POST">
  <div class="form-group">
    <label class="col">CSV-Datei:</label>
    <input class="col" name="csv-file" type="file" />
  </div>

  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

  <div class="form-group">
    <button class="w-100" type="submit">Schüler importieren</button>
  </div>
</form>
