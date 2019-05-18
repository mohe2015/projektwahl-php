<?php
require_once '../head.php';
$projects = Projects::all();
?>

<h1>Projektliste</h1>

<?php foreach ($projects as $project) :?>
  <h2><?php echo htmlspecialchars($project->title) ?></h2>
<?php endforeach;?>
