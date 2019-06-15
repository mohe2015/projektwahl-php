<?php
class Student extends User {
  public $project_leader;
  public $class;
  public $grade;
  public $away;
  public $in_project;

  public function __construct($data = null) {
    if (is_array($data)) {
      $this->update($data);
    }
    $this->type = "student";
  }

  public function update($data) {
    User::update($data);
    $this->project_leader = $data['project_leader'] ?: $this->project_leader;
    $this->class = $data['class'] ?: $this->class;
    $this->grade = $data['grade'] ?: $this->grade;
    $this->away = $data['away'] ?: $this->away;
    $this->in_project = $data['in_project'] ?: $this->in_project;
  }

  public function getValidationErrors() {
    $validation_errors = User::getValidationErrors();
    if (empty($this->project_leader)) {
      //array_push($validation_errors, "Projektleiter fehlt!");
    }
    if (empty($this->class)) {
      array_push($validation_errors, "Klasse fehlt!");
    }
    if (empty($this->grade)) {
      array_push($validation_errors, "Jahrgang fehlt!");
    }
    if (empty($this->away)) {
      //array_push($validation_errors, "Abwesend fehlt!");
    }
    if (empty($this->in_project)) {
      //array_push($validation_errors, "Projekt fehlt!");
    }
    return $validation_errors;
  }
}

class Students {
  public function find($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id AND type = 'student'");
    return apcu_entry("user-$this->id", function($key) {
      $stmt->execute(array('id' => $id));
      return $stmt->fetchObject('Student');
    });
  }
  public function all() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'student';");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Student');
  }
}
?>
