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
  public function find($title) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM projects WHERE title = :title');
    $stmt->execute(array('title' => $title));
    $stmt->setFetchMode(PDO::FETCH_CLASS, 'Project');
    return $stmt->fetch();
  }
  public function validate(\Project $project) {
    $validation_errors = array();
    if (empty($project->title)) {
      array_push($validation_errors, "Titel fehlt!");
    }
    if (!empty($validation_errors)) {
      throw new ValidationError(implode("<br>", $validation_errors));
    }
  }  
  public function save(\Project $project) {
    Projects::validate($project);
    global $db;
    $stmt = $db->prepare('INSERT INTO projects (title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, requirements, random_assignments) VALUES (:title, :info, :place, :costs, :min_grade, :max_grade, :min_participants,
 :max_participants, :presentation_type, :requirements, :random_assignments)');
    return $stmt->execute(array(
      'title' => $title,
      'info' => $info,
      'place' => $place,
      'costs' => $costs,
      'min_grade' => $min_grade,
      'max_grade' => $max_grade,
      'min_participants' => $min_participants,
      'max_participants' => $max_participants,
      'presentation_type' => $presentation_type,
      'requirements' => $requirements,
      'random_assignments' => $random_assignments
    ));
  }
}

?>
