<?php
$allowed_users = array("admin");
require_once __DIR__ . '/../head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $timers = new Timers();
    $timers->startTimer('import');
    $db->beginTransaction();
    if (($handle = fopen($_FILES['csv-file']['tmp_name'], "r")) !== FALSE) {
        $header = fgetcsv($handle, 1000, ",");
        while ($csv_data = fgetcsv($handle, 1000, ",")) {
            $data = array_combine($header, $csv_data);
            $student = new Student();
            $student->name = trim($data["name"] ?? "") ?: trim($data["first-name"]) . " " . trim($data["last-name"]);
            $student->class = trim($data["class"]);
            $student->grade = trim($data["grade"]);
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
    <label class="col">CSV-Datei (name/first-name+last-name, class, grade):</label>
    <input class="col" name="csv-file" type="file" />
  </div>

  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

  <div class="form-group">
    <button class="w-100" type="submit">Schüler importieren</button>
  </div>
</form>
