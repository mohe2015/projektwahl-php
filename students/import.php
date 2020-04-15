<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
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
        header("Location: $ROOT/students");
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

<label>CSV-Datei (name/first-name+last-name, class, grade):</label>
<input name="csv-file" type="file" />

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<button type="submit">Schüler importieren</button>

</form>
