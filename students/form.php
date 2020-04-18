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
?>
<form method="post">

<label class="form-label" for="student-new-name">Name*:</label>
<input class="form-control" id="student-new-name" autofocus type="text" name="name" value="<?php echo htmlspecialchars($student->name) ?>" />

<label class="form-label" for="student-new-class">Klasse*:</label>
<input class="form-control" id="student-new-class" type="text" name="class" value="<?php echo htmlspecialchars($student->class) ?>" />

<label class="form-label" for="student-new-grade">Jahrgang*:</label>
<input class="form-control" id="student-new-grade" type="number" name="grade" value="<?php echo htmlspecialchars($student->grade) ?>" />

<div class="form-check">
  <input id="student-new-away" class="form-check-input" type="checkbox" value="" name="away" <?php echo (!empty($student->away)) ? "checked" : "" ?>>
  <label for="student-new-away" class="form-check-label">
    Abwesend
  </label>
</div>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<button class="btn btn-primary" type="submit">Schüler speichern</button>

</form>
