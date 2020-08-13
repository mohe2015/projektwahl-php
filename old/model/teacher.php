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
    $stmt->execute(array('id' => $id));
    $result = $stmt->fetchObject('Teacher');
    return $result;
  }
  public function all() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher' ORDER BY name");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Teacher');
    return $result;
  }

  public function allWithoutPasswords() {
    global $db;
    $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher' AND password IS NULL ORDER BY name");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'Teacher');
    return $result;
  }
}
?>
