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
    User::update($data); // TODO FIXME prevent updating type - this would be a privilege escalation
    $this->project_leader = $data['project_leader'] ?? $this->project_leader;
    $this->class = $data['class'] ?? $this->class;
    $this->grade = $data['grade'] ?? $this->grade;
    $this->away = $data['away'] ?? $this->away;
    $this->in_project = $data['in_project'] ?? $this->in_project;
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
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchObject('Student');
    return $result;
  }
  public function all() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'student' ORDER BY grade, class, name");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Student');
    return $result;
  }

  public function allWithoutPasswords() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'student' AND password IS NULL ORDER BY grade, class, name");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Student');
    return $result;
  }
}
?>
