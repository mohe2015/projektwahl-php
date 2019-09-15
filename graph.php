<?php
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
