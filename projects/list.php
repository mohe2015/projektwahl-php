<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/../head.php';

$projects = Projects::all();
?>

<h1>Projektliste</h1>

<?php foreach ($projects as $project) :?>
<?php
require 'project.php';
?>
<?php endforeach;?>
