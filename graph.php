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
      "label": "<?php echo $project->title ?>",
      "x": 0,
      "y": 0,
      "size": <?php echo $project->max_participants ?>
    },
<?php endforeach; ?>
<?php foreach($students as $student): ?>
    {
      "id": "s<?php echo $student->id ?>",
      "label": "<?php echo $student->name ?>",
      "x": 0,
      "y": 0,
      "size": 10
    },
<?php endforeach; ?>
    {
      "id": "n2",
      "label": "And a last one",
      "x": 1,
      "y": 3,
      "size": 1
    },
    {
      "id": "n3",
      "label": "And a last one",
      "x": 1,
      "y": 3,
      "size": 1
    }
  ],
  "edges": [
  <?php foreach($choices as $choice): ?>
    {
      "id": "c<?php echo $choice->student + ($choice->project << 32) ?>",
      "source": "s<?php echo $choice->student ?>",
      "target": "p<?php echo $choice->project ?>"
    },
  <?php endforeach; ?>
    {
      "id": "",
      "source": "n2",
      "target": "n3"
    }
  ]
}
