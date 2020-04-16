<?php
/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
require_once __DIR__ . '/record.php';

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
      $this->update($data);
    }
  }

  public function update($data) {
    $this->title = $data['title'] ?? $this->title;
    $this->info = $data['info'] ?? $this->info;
    $this->place = $data['place'] ?? $this->place;
    $this->costs = $data['costs'] ?? $this->costs ?? 0;
    $this->min_grade = $data['min_grade'] ?? $this->min_grade ?? 5;
    $this->max_grade = $data['max_grade'] ?? $this->max_grade ?? 13;
    $this->min_participants = $data['min_participants'] ?? $this->min_participants ?? 5;
    $this->max_participants = $data['max_participants'] ?? $this->max_participants ?? 25;
    $this->presentation_type = $data['presentation_type'] ?? $this->presentation_type;
    $this->requirements = $data['requirements'] ?? $this->requirements;
    $this->random_assignments = $data['random_assignments'] ?? $this->random_assignments ?? true;
    $this->supervisors = $data['supervisors'] ?? $this->supervisors ?? array();
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
    if (blank($this->costs)) {
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
      //array_push($validation_errors, "Präsentationsart fehlt!");
    }
    if (empty($this->requirements)) {
      //array_push($validation_errors, "\"Ich benötige\" fehlt!");
    }
    if (!isset($this->random_assignments)) {
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
        'random_assignments' => $this->random_assignments ? 1 : 0
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
        'random_assignments' => $this->random_assignments ? 1 : 0
      ));
    }
    if (isset($this->supervisors)) {
      $db->beginTransaction();

      $stmt = $db->prepare('UPDATE users SET project_leader = NULL WHERE project_leader = :id');
      $stmt->execute(array(
        'id' => $this->id
      ));

      $stmt = $db->prepare('UPDATE users SET project_leader = :id WHERE id = :user_id');
      foreach ($this->supervisors as $project_leader) {
        $stmt->execute(array(
          'id' => $this->id,
          'user_id' => $project_leader // TODO this should not overwrite old data?
        ));
      }

      $db->commit();
    }
    return $this;
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
    $result = $stmt->fetchObject('Project');
    return $result;
  }
  public function all() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM projects ORDER BY title;');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Project');
    return $result;
  }

  public function allWithRanks() {
    global $db;
    $stmt = $db->prepare('SELECT id, title, min_grade, max_grade, choices.rank FROM projects LEFT JOIN choices ON id = choices.project AND choices.student = :student ORDER BY rank=0 DESC, rank ASC;');
    $stmt->execute(array('student' => end($_SESSION['users'])->id));
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Project');
  }

  public function findWithProjectLeadersAndMembers($id) {
    global $db;
    $stmt = $db->prepare("SELECT projects.*, users.name, users.project_leader, users.in_project FROM projects LEFT JOIN users ON users.project_leader = projects.id OR users.in_project = projects.id WHERE projects.id = :id;");
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Project');
    return $result;
  }
}

?>
