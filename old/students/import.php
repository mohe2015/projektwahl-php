<?php
/*
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
$allowed_users = array("admin");
require_once __DIR__ . '/../header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
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
        header("Location: $ROOT/students");
        die();
    } else {
      throw new Exception('<div class="alert alert-danger" role="alert">Keine Datei ausgewählt!</div>');
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    $db->rollback();
  }
}
?>
<!doctype html>
<html lang="de">
<head>
<?php require __DIR__ . '/../head.php' ?>
</head>
<body class="bg-dark text-white">
<?php require __DIR__ . '/../nav.php' ?>
<div class="container">

<form enctype="multipart/form-data" method="POST">

<div class="form-file">
  <input type="file" class="form-file-input" id="customFile" name="csv-file" accept="text/csv">
  <label class="form-file-label" for="customFile">
    <span class="form-file-text">CSV-Datei (name/first-name+last-name, class, grade) auswählen</span>
    <span class="form-file-button">Durchsuchen</span>
  </label>
</div>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<button class="btn btn-primary" type="submit">Schüler importieren</button>

</form>



</div>
<?php require __DIR__ . '/../footer.php' ?>
</body>
</html>
