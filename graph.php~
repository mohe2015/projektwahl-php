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
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/header.php';

$projects = Projects::all();
$students = Students::all();
$choices = Choices::all();

header('Content-type: application/json');
?>
{
  "nodes": [
<?php foreach($projects as $project): ?>
    {
      "id": "p<?php echo $project->id ?>",
      "label": "<?php echo $project->title ?>"
    },
<?php endforeach; ?>
<?php foreach($students as $key => $student): ?>
    {
      "id": "s<?php echo $student->id ?>",
      "label": "<?php echo $student->name ?>"
    }<?php
    if ($key !== count($students)-1) {
            echo ",";
    }
endforeach; ?>
  ],
  "links": [
<?php foreach($choices as $key => $choice): ?>
    {
      "id": "c<?php echo $choice->student + ($choice->project << 32) ?>",
      "source": "s<?php echo $choice->student ?>",
      "target": "p<?php echo $choice->project ?>"
    }<?php
    if ($key !== count($choices)-1) {
            echo ",";
    }
endforeach; ?>
  ]
}
