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
  <h2 class="text-center"><?php echo htmlspecialchars($project->title) ?> <a role="button" class="btn btn-primary d-print-none" href="<?php echo $ROOT ?>/projects/edit.php?<?php echo $project->id ?>"><i class="fas fa-pen"></i></a> <a role="button" class="btn btn-primary d-print-none" href="<?php echo $ROOT ?>/projects/delete.php?<?php echo $project->id ?>"><i class="fas fa-trash"></i></a></h2>
  <b>Info: </b><?php echo htmlspecialchars($project->info) ?><br>
  <b>Ort/Raum: </b><?php echo htmlspecialchars($project->place) ?><br>
  <b>Ich benötige: </b><?php echo htmlspecialchars($project->requirements) ?><br>
  <b>Präsentationsart: </b><?php echo htmlspecialchars($project->presentation_type) ?><br>
  <b>Kosten: </b><?php echo htmlspecialchars($project->costs) ?><br>
  <b>Jahrgangsstufe: </b><?php echo htmlspecialchars($project->min_grade) ?> - <?php echo htmlspecialchars($project->max_grade) ?><br>
  <b>Teilnehmeranzahl: </b><?php echo htmlspecialchars($project->min_participants) ?> - <?php echo htmlspecialchars($project->max_participants) ?><br>
  <b>Projektleiter: </b>
<?php
$project_leaders = array_filter($project_with_project_leaders_and_members, function ($user) {
  return $user->project_leader != NULL;
});
echo join(', ', array_map(function($project_leader) {
    return $project_leader->name;
}, $project_leaders));
?>
  <br>
  <b>Teilnehmer: </b>
<?php
$members = array_filter($project_with_project_leaders_and_members, function ($user) {
  return $user->project_leader == NULL;
});
echo join(', ', array_map(function($member) {
    return $member->name;
}, $members));
?>
  <br>
  <b>Zufällige Projektzuweisungen erlaubt: </b><?php echo htmlspecialchars($project->random_assignments) ? "ja" : "nein" ?><br>
</div>
