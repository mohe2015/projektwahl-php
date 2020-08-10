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
<form novalidate method="post" class="needs-validation">

<div class="mb-3">
  <label class="form-label" for="student-new-name">Name*:</label>
  <input class="form-control" id="student-new-name" required autofocus type="text" name="name" value="<?php echo htmlspecialchars($student->name) ?>" />
  <div class="invalid-feedback">
    Name fehlt!
  </div>
</div>

<div class="mb-3">
  <label class="form-label" for="student-new-class">Klasse*:</label>
  <input class="form-control" id="student-new-class" required type="text" name="class" value="<?php echo htmlspecialchars($student->class) ?>" />
  <div class="invalid-feedback">
    Klasse fehlt!
  </div>
</div>

<div class="mb-3">
  <label class="form-label" for="student-new-grade">Jahrgang*:</label>
  <input class="form-control" id="student-new-grade" min="1" max="13" required type="number" name="grade" value="<?php echo htmlspecialchars($student->grade) ?>" />
  <div class="invalid-feedback">
    Jahrgang muss zwischen 1 und 13 liegen!
  </div>
</div>

<div class="form-check mb-3">
  <input id="student-new-away" class="form-check-input" type="checkbox" value="" name="away" <?php echo (!empty($student->away)) ? "checked" : "" ?>>
  <label for="student-new-away" class="form-check-label">
    Abwesend
  </label>
</div>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<div class="mb-3">
  <button class="btn btn-primary" type="submit">Schüler speichern</button>
</div>

</form>
