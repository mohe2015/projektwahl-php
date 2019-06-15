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
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND type = 'teacher'");
    return apcu_entry("user-$this->id", function($key) {
      $stmt->execute(array('id' => $id));
      return $stmt->fetchObject('Teacher');
    });
  }
  public function all() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher';");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Teacher');
  }
}
?>
