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

<label for="student-new-name">Name*:</label>
<input id="student-new-name" autofocus class="form-control" type="text" name="name" value="<?php echo htmlspecialchars($student->name) ?>" />

<label>Klasse*:</label>
<input class="form-control" type="text" name="class" value="<?php echo htmlspecialchars($student->class) ?>" />

<label>Jahrgang*:</label>
<input class="form-control" type="number" name="grade" value="<?php echo htmlspecialchars($student->grade) ?>" />

<input id="student-new-away" type="checkbox" class="form-check-input" name="away" <?php echo (!empty($student->away)) ? "checked" : "" ?>>
<label class="form-check-label" for="student-new-away">Abwesend</label>

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />

<button type="submit">Schüler speichern</button>

</form>
