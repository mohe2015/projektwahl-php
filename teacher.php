<?php
class Teacher extends User {
  public $project_leader;

  public function __construct($data = null) {
    User::__construct($data);
    if (is_array($data)) {
      $this->update($data);
    }
    $this->type = "teacher";
  }

  public function update($data) {
    User::update($data);
    $this->project_leader = $data['project_leader'] ?? $this->project_leader;
  }
}

class Teachers {
  public function find($id) {
    // TODO check that's a teacher
    $result = apcu_fetch("user-$this->id");
    if ($result) {
      return $result;
    }
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND type = 'teacher'");
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchObject('Teacher');
    apcu_add("user-$this->id", $result);
    return $result;
  }
  public function all() {
    $result = apcu_fetch("teachers");
    if ($result) {
      return $result;
    }
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher';");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Teacher');
    apcu_add("teachers", $result);
    return $result;
  }
}
?>
