<?php
/*
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
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
    if ($this->rank == 0) {
      $this->delete();
    } else {
      global $db;
      $stmt = $db->prepare('INSERT INTO choices (project, student, rank) VALUES (:project, :student, :rank) ON CONFLICT (project, student) DO UPDATE SET rank = :rank1');
      $stmt->execute(array(
        'project' => $this->project,
        'student' => $this->student,
        'rank' => $this->rank,
        'rank1' => $this->rank,
      ));
    }
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

  public function allWithUsers() {
    global $db;
    $stmt = $db->prepare("SELECT users.*, choices.* FROM users LEFT JOIN choices ON id = choices.student AND choices.rank != 0 WHERE type = 'student' AND away = FALSE ORDER BY class,name;"); // TODO FIXME rank!=0
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Choice');
  }

  public function allWithUsersWithAway() {
    global $db;
    $stmt = $db->prepare("SELECT users.*, choices.* FROM users LEFT JOIN choices ON id = choices.student AND choices.rank != 0 WHERE type = 'student' ORDER BY class,name;"); // TODO FIXME rank!=0
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_CLASS, 'Choice');
  }

  public function groupChoices($choices) {
    $grouped_choices = array();
    foreach ($choices as $choice) {
      if ($choice->rank === NULL) {
        $grouped_choices[$choice->id] = array();
      } else {
        $grouped_choices[$choice->id][] = $choice;
      }
    }
    return $grouped_choices;
  }

  public function validateChoices($grouped_choices, $assoc_students) {
    foreach ($grouped_choices as $student_id => $student_choices) {
      $student = $assoc_students[$student_id];
      $rank_count = array(
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0
      );
      foreach ($student_choices as $choice) {
        $rank_count[$choice->rank]++;
      }
      if ($rank_count[1] == 1 && $rank_count[2] == 1 && $rank_count[3] == 1 && $rank_count[4] == 1 && $rank_count[5] == 1) {
        $assoc_students[$student_id]->valid = true;
      } else {
        $assoc_students[$student_id]->valid = false;
      }
    }
    return $assoc_students;
  }
}

?>
