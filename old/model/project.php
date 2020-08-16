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

class Project extends Record {
  
  public function save() {
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