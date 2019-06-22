<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$projects = Projects::all();
?>

<h1 class="print-display-none">Projektliste</h1>

<!-- TODO remove space + remove some info from the list or put some things on one line to not waste space -->

<?php foreach ($projects as $project):
$project_with_project_leaders_and_members = Projects::findWithProjectLeadersAndMembers($project->id); // TODO FIXME this is an N + 1 query which is really bad
?>
<?php
require 'project.php';
?>
<?php endforeach;?>
