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
<div style="page-break-inside: avoid;">
  <h2><?php echo htmlspecialchars($student->name) ?> <a class="print-display-none" href="<?php echo $ROOT ?>/students/edit.php?<?php echo $student->id ?>"><i class="fas fa-pen"></i></a> <a class="print-display-none" href="<?php echo $ROOT ?>/students/delete.php?<?php echo $student->id ?>"><i class="fas fa-trash"></i></a></h2>
  <b>Klasse: </b><?php echo htmlspecialchars($student->class) ?><br>
  <b>Jahrgang: </b><?php echo htmlspecialchars($student->grade) ?><br>
  <b>Abwesend? </b><?php echo htmlspecialchars($student->away) ? "ja" : "nein" ?><br>
  <b>Projektleiter in: </b><?php echo htmlspecialchars(Projects::find($student->project_leader)->title) ?><br>
  <b>In Projekt: </b><?php echo htmlspecialchars(Projects::find($student->in_project)->title) ?><br>
</div>
