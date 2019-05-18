<?php
require_once 'record.php';

class Project extends Record {
  public $id;
  public $title;
  public $info;
  public $place;
  public $costs;
  public $min_grade;
  public $max_grade;
  public $min_participants;
  public $max_participants;
  public $presentation_type;
  public $requirements;
  public $random_assignments;
  public function __construct($data = null) {
    if (is_array($data)) {
      $this->title = $data['title'];
      $this->info = $data['info'];
      $this->place = $data['place'];
      $this->costs = $data['costs'];
      $this->min_grade = $data['min_grade'];
      $this->max_grade = $data['max_grade'];
      $this->min_participants = $data['min_participants'];
      $this->max_participants = $data['max_participants'];
      $this->presentation_type = $data['presentation_type'];
      $this->requirements = $data['requirements'];
      $this->random_assignments = $data['random_assignments'];
    }
  }
  public function getValidationErrors() {
    $validation_errors = array();
    if (empty($this->title)) {
      array_push($validation_errors, "Titel fehlt!");
    }
    if (empty($this->info)) {
      array_push($validation_errors, "Info fehlt!");
    }
    if (empty($this->place)) {
      array_push($validation_errors, "Ort/Raum fehlt!");
    }
    if (empty($this->costs)) {
      array_push($validation_errors, "Kosten fehlen!");
    }
    if (empty($this->min_grade)) {
      array_push($validation_errors, "Mindestjahrgang fehlt!");
    }
    if (empty($this->max_grade)) {
      array_push($validation_errors, "Maximaljahrgang fehlt!");
    }
    if (empty($this->min_participants)) {
      array_push($validation_errors, "Mindestteilnehmeranzahl fehlt!");
    }
    if (empty($this->max_participants)) {
      array_push($validation_errors, "Maximalteilnehmeranzahl fehlt!");
    }
    if (empty($this->presentation_type)) {
      array_push($validation_errors, "Präsentationsart fehlt!");
    }
    if (empty($this->requirements)) {
      array_push($validation_errors, "\"Ich benötige\" fehlt!");
    }
    if (empty($this->random_assignments)) {
      array_push($validation_errors, "\"Zufällige Projektzuweisungen erlaubt\" fehlt!");
    }
    return $validation_errors;
  }

  public function save() {
    $this->validate();
    global $db;
    if (empty($this->id)) {
      $stmt = $db->prepare('INSERT INTO projects (title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, requirements, random_assignments) VALUES (:title, :info, :place, :costs, :min_grade, :max_grade, :min_participants,
      :max_participants, :presentation_type, :requirements, :random_assignments)');
      $stmt->execute(array(
        'title' => $this->title,
        'info' => $this->info,
        'place' => $this->place,
        'costs' => $this->costs,
        'min_grade' => $this->min_grade,
        'max_grade' => $this->max_grade,
        'min_participants' => $this->min_participants,
        'max_participants' => $this->max_participants,
        'presentation_type' => $this->presentation_type,
        'requirements' => $this->requirements,
        'random_assignments' => !empty($this->random_assignments)
      ));
      $project->id = $db->lastInsertId();
    } else {
      $stmt = $db->prepare('UPDATE projects SET title = :title, info = :info, place = :place, costs = :costs, min_grade = :min_grade, max_grade = :max_grade, min_participants = :min_participants, max_participants = :max_participants, presentation_type = :presentation_type, requirements = :requirements, random_assignments = :random_assignments WHERE id = :id');
      $stmt->execute(array(
        'id' => $this->id,
        'title' => $this->title,
        'info' => $this->info,
        'place' => $this->place,
        'costs' => $this->costs,
        'min_grade' => $this->min_grade,
        'max_grade' => $this->max_grade,
        'min_participants' => $this->min_participants,
        'max_participants' => $this->max_participants,
        'presentation_type' => $this->presentation_type,
        'requirements' => $this->requirements,
        'random_assignments' => !empty($this->random_assignments)
      ));
    }
  }

  public function delete() {
    global $db;
    $stmt = $db->prepare('DELETE FROM projects WHERE id = :id;');
    $stmt->execute(array(
      'id' => $this->id
    ));
  }
}
class Projects {
  public function find($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM projects WHERE id = :id');
    $stmt->execute(array('id' => $id));
    return $stmt->fetchObject('Project');
  }
  public function all() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM projects;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Project');
  }
}

?>
