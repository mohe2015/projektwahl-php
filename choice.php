<?php
require_once __DIR__ . '/record.php';

// a choice of a project a student can make
class Choice extends Record {
  public $project;
  public $student;
  public $rank;

  public function __construct($data = null) {
    if (is_array($data)) {
      $this->update($data);
    }
  }

  public function update($data) {
    $this->project = $data['project'] ?? $this->project;
    $this->student = $data['student'] ?? $this->student;
    $this->rank = $data['rank'] ?? $this->rank;
  }

  public function getValidationErrors() {
    $validation_errors = array();
    if (blank($this->project)) {
      array_push($validation_errors, "Projekt fehlt!");
    }
    if (blank($this->student)) {
      array_push($validation_errors, "Schüler fehlt!");
    }
    if (blank($this->rank)) {
      array_push($validation_errors, "Rang fehlt!");
    }
    return $validation_errors;
  }

  public function save() {
    $this->validate();
    global $db;
    $stmt = $db->prepare('INSERT INTO choices (project, student, rank) VALUES (:project, :student, :rank) ON CONFLICT (project, student) DO UPDATE SET rank = :rank1');
    $stmt->execute(array(
      'project' => $this->project,
      'student' => $this->student,
      'rank' => $this->rank,
      'rank1' => $this->rank,
    ));
    // TODO fix rank 0
  }

  public function delete() {
    global $db;
    $stmt = $db->prepare('DELETE FROM choices WHERE project = :project AND student = :student;');
    $stmt->execute(array(
      'project' => $this->project,
      'student' => $this->student
    ));
  }
}
class Choices {
  public function find($student, $project) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM choices WHERE project = :project AND student = :student;');
    $stmt->execute(array(
      'project' => $project,
      'student' => $student
    ));
    return $stmt->fetchObject('Choice');
  }
  public function all() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM choices WHERE rank != 0 ORDER BY student;'); // TODO FIXME rank!=0
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Choice');
  }
}

?>
