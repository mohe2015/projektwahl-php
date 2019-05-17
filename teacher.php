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
}
?>
