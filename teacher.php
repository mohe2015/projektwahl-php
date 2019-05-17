<?php
class Teacher extends User {
  public $project_leader;

  public function __construct($data = null) {
    User::__construct($data);
    if (is_array($data)) {
      $this->project_leader = $data['project_leader'];
    }
    $this->type = "teacher";
  }
}

class Teachers {
  public function find($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE id = :id AND type = "teacher"');
    $stmt->execute(array('id' => $id));
    return $stmt->fetchObject('Teacher');
  }
  public function all() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM users WHERE type = "teacher";');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Teacher');
  }
  // TODO put into base class
  public function save(\Teacher $student) {
    $user->validate();
    global $db;
    if (empty($student->id)) {
      $stmt = $db->prepare('INSERT INTO users (name, password, type, project_leader, class, grade, away, in_project) VALUES (:name, :password, :type, :project_leader, :class, :grade, :away, :in_project)');
      $stmt->execute(array(
        'name' => $student->name,
        'password' => $student->password,
        'type' => $student->type,
        'project_leader' => $student->project_leader,
        'class' => $student->class,
        'grade' => $student->grade,
        'away' => $student->away,
        'in_project' => $student->in_project
      ));
      $student->id = $db->lastInsertId();
    } else {
      $stmt = $db->prepare('UPDATE users SET name = :name, password = :password, type = :type, project_leader = :project_leader, class = :class, grade = :grade, away = :away, in_project = :in_project WHERE id = :id');
      $stmt->execute(array(
        'id' => $student->id,
        'name' => $student->name,
        'password' => $student->password,
        'type' => $student->type,
        'project_leader' => $student->project_leader,
        'class' => $student->class,
        'grade' => $student->grade,
        'away' => $student->away,
        'in_project' => $student->in_project
      ));
    }
  }
}
?>
