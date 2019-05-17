<?php
class ValidationError extends Exception { }
class Project {
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
  public function validate(\Project $project) {
    $validation_errors = array();
    if (empty($project->title)) {
      array_push($validation_errors, "Titel fehlt!");
    }
    if (empty($project->info)) {
      array_push($validation_errors, "Info fehlt!");
    }
    if (empty($project->place)) {
      array_push($validation_errors, "Ort/Raum fehlt!");
    }
    if (empty($project->costs)) {
      array_push($validation_errors, "Kosten fehlen!");
    }
    if (empty($project->min_grade)) {
      array_push($validation_errors, "Mindestjahrgang fehlt!");
    }
    if (empty($project->max_grade)) {
      array_push($validation_errors, "Maximaljahrgang fehlt!");
    }
    if (empty($project->min_participants)) {
      array_push($validation_errors, "Mindestteilnehmeranzahl fehlt!");
    }
    if (empty($project->max_participants)) {
      array_push($validation_errors, "Maximalteilnehmeranzahl fehlt!");
    }
    if (empty($project->presentation_type)) {
      array_push($validation_errors, "Präsentationsart fehlt!");
    }
    if (empty($project->requirements)) {
      array_push($validation_errors, "\"Ich benötige\" fehlt!");
    }
    if (empty($project->random_assignments)) {
      array_push($validation_errors, "\"Zufällige Projektzuweisungen erlaubt\" fehlt!");
    }
    if (!empty($validation_errors)) {
      throw new ValidationError(implode("<br>", $validation_errors));
    }
  }
  public function save(\Project $project) {
    Projects::validate($project);
    global $db;
    if (empty($project->id)) {
      $stmt = $db->prepare('INSERT INTO projects (title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, requirements, random_assignments) VALUES (:title, :info, :place, :costs, :min_grade, :max_grade, :min_participants,
      :max_participants, :presentation_type, :requirements, :random_assignments)');
      $stmt->execute(array(
        'title' => $project->title,
        'info' => $project->info,
        'place' => $project->place,
        'costs' => $project->costs,
        'min_grade' => $project->min_grade,
        'max_grade' => $project->max_grade,
        'min_participants' => $project->min_participants,
        'max_participants' => $project->max_participants,
        'presentation_type' => $project->presentation_type,
        'requirements' => $project->requirements,
        'random_assignments' => !empty($project->random_assignments)
      ));
      $project->id = $db->lastInsertId();
    } else {
      $stmt = $db->prepare('UPDATE projects SET title = :title, info = :info, place = :place, costs = :costs, min_grade = :min_grade, max_grade = :max_grade, min_participants = :min_participants, max_participants = :max_participants, presentation_type = :presentation_type, requirements = :requirements, random_assignments = :random_assignments WHERE id = :id');
      $stmt->execute(array(
        'id' => $project->id,
        'title' => $project->title,
        'info' => $project->info,
        'place' => $project->place,
        'costs' => $project->costs,
        'min_grade' => $project->min_grade,
        'max_grade' => $project->max_grade,
        'min_participants' => $project->min_participants,
        'max_participants' => $project->max_participants,
        'presentation_type' => $project->presentation_type,
        'requirements' => $project->requirements,
        'random_assignments' => !empty($project->random_assignments)
      ));
    }
  }
}

?>
