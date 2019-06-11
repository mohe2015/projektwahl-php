<?php
$allowed_users = array("admin", "teacher");
require_once __DIR__ . '/header.php';

$projects = Projects::all();
$students = Students::all();
$choices = Choices::all();

header('Content-type: application/json');
?>
[
<?php foreach($projects as $project): ?>
  {
    "data": {
      "id": "p<?php echo $project->id ?>",
      "label": "<?php echo $project->title ?>"
    }
  },
<?php endforeach; ?>
<?php foreach($students as $student): ?>
  {
    "data": {
      "id": "s<?php echo $student->id ?>",
      "label": "<?php echo $student->name ?>"
    }
  },
<?php endforeach; ?>
<?php foreach($choices as $key => $choice): ?>
  {
    "data": {
      "id": "c<?php echo $choice->student + ($choice->project << 32) ?>",
      "source": "s<?php echo $choice->student ?>",
      "target": "p<?php echo $choice->project ?>"
    }
  }<?php
  if ($key !== count($choices)-1) {
          echo ",";
  }
endforeach; ?>
]
