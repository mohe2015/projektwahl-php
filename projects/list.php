<?php
require_once '../head.php';
$projects = Projects::all();
?>

<h1>Projektliste</h1>

<?php foreach ($projects as $project) :?>
<?php
require_once '../project/project.php';
?>
<?php endforeach;?>
