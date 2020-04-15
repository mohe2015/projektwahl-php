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

// Import teachers from a .csv file. The file needs to have one column (name) and no header.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    if (($handle = fopen($_FILES['csv-file']['tmp_name'], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            if ($num != 1) {
              throw new Exception("nur eine Spalte erlaubt!");
            }
            $teacher = new Teacher();
            $teacher->name = $data[0];
            $teacher->password = NULL;
            $teacher->save();
        }
        fclose($handle);

        $db->commit();
        header("Location: $ROOT/teachers");
        die();
    } else {
      throw new Exception("Keine Datei ausgewählt!");
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    $db->rollback();
  }
}
?>
<form enctype="multipart/form-data" method="POST">

<div class="form-file">
  <input type="file" class="form-file-input" id="customFile" name="csv-file" accept="text/csv">
  <label class="form-file-label" for="customFile">
    <span class="form-file-text">CSV-Datei auswählen</span>
    <span class="form-file-button">Durchsuchen</span>
  </label>
</div>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<button type="submit">Lehrer importieren</button>

</form>
