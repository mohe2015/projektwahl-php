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

class Record {
    private bool $new;

    protected static function getInsertStatement() {
        throw new Error("not implemented");
    }

    protected static function getUpdateStatement() {
        throw new Error("not implemented");
    }

    public function __construct(?array $data) {
        if ($data !== null) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->new = $data !== null;
    }

    public function save() {
        global $db;
        $reflect = new ReflectionClass($this);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $props_arr = array();
        foreach ($props as $prop) {
            if ($this->new && $prop->getName() === "id") continue;
            $props_arr[$prop->getName()] = $prop->getValue($this); 
        }
        if ($this->new) {
            error_log(print_r($props_arr, true), 0);
            error_log(print_r(static::getInsertStatement(), true), 0);
            static::getInsertStatement()->execute($props_arr);
            if (property_exists($this, 'id')) {
                $this->id = $db->lastInsertId();
            }
            $this->new = false;
        } else {
            static::getUpdateStatement()->execute($props_arr);
        }
    }
}

class Session extends Record {
    public string $session_id;
    public int $created_at;
    public int $updated_at;

    protected static $insert_stmt = null;
    protected static $update_stmt = null;

    protected static function getInsertStatement() {
        global $db;
        if (null === self::$insert_stmt) {
            self::$insert_stmt = $db->prepare('INSERT INTO sessions (session_id, created_at, updated_at) VALUES (:session_id, :created_at, :updated_at)');
        }
        return self::$insert_stmt;
    }

    protected static function getUpdateStatement() {
        global $db;
        if (null === self::$update_stmt) {
            self::$update_stmt = $db->prepare('UPDATE sessions SET created_at = :created_at, updated_at = :updated_at WHERE session_id = :session_id');
        }
        return self::$update_stmt;
    }
}

class Sessions {
    protected static $find_stmt = null;

    protected static function getFindStatement() {
        global $db;
        if (null === self::$find_stmt) {
            self::$find_stmt = $db->prepare('SELECT * FROM sessions WHERE session_id = :session_id');
        }
        return self::$find_stmt;
    }

    public static function find($session_id): Session {
        $stmt = self::getFindStatement();
        $stmt->execute(array('session_id' => $session_id));
        $result = $stmt->fetchObject('Session', array(null));
        $result->new = false;
        return $result;
    }
}

class UserSession extends Record {
    public ?int $id;
    public string $session_id;
    public int $user_id;

    protected static $insert_stmt = null;
    protected static $update_stmt = null;

    protected static function getInsertStatement() {
        global $db;
        if (null === self::$insert_stmt) {
            self::$insert_stmt = $db->prepare("INSERT INTO session_users (session_id, user_id) VALUES (:session_id, :user_id)");
        }
        return self::$insert_stmt;
    }

    protected static function getUpdateStatement() {
        throw new Error("not implemented");
    }
}

class UserSessions {
    protected static $find_current_stmt = null;

    protected static function getFindCurrentStatement() {
        global $db;
        if (null === self::$find_current_stmt) {
            self::$find_current_stmt = $db->prepare('SELECT users.* FROM session_users, users WHERE session_users.session_id = :session_id and session_users.user_id = users.id ORDER BY session_users.id DESC LIMIT 1;');
        }
        return self::$find_current_stmt;
    }

    public static function getCurrent($session_id): User {
        $stmt = self::getFindCurrentStatement();
        $stmt->execute(array('session_id' => $session_id));
        $result = $stmt->fetchObject('User', array(null));
        $result->new = false;
        return $result;
    }
}

class Participant extends User {

    public function __construct(?array $array) {
        parent::__construct($array);
        $this->type = "participant";
    }
}

class User extends Record {
    public ?int $id;
    public string $name;
    public ?string $password_hash = null;
    public string $type;
    public bool $password_changed = false;

    public ?int $project_leader = null;
    public ?string $class;
    public ?int $age;
    public ?bool $away = false;
    public ?int $in_project = null;

    protected static $insert_stmt = null;
    protected static $update_stmt = null;

    protected static function getInsertStatement() {
        global $db;
        if (null === self::$insert_stmt) {
            self::$insert_stmt = $db->prepare('INSERT INTO users (name, password_hash, type, password_changed, project_leader, class, age, away, in_project) VALUES (:name, :password_hash, :type, :password_changed, :project_leader, :class, :age, :away, :in_project)');
        }
        return self::$insert_stmt;
    }

    protected static function getUpdateStatement() {
        global $db;
        if (null === self::$update_stmt) {
            self::$update_stmt = $db->prepare('UPDATE users SET name = :name, password_hash = :password_hash, type = :type, password_changed = :password_changed, project_leader = :project_leader, class = :class, age = :age, away = :away, in_project = :in_project WHERE id = :id');
        }
        return self::$update_stmt;
    }

    public function __construct(?array $array) {
        parent::__construct($array);
    }
}

class Users {
    public static function find($id) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchObject('User', array(null));
        $result->new = false;
        return $result;
    }

    public static function findByName($name) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(array('name' => $name));
        $result = $stmt->fetchObject('User', array(null));
        $result->new = false;
        return $result;
    }

    public static function all() {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE type = 'project-manager' OR type = 'participant';");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'User', array(null));
        $result->new = false;
        return $result;
    }
}

class Project extends Record {

    public ?int $id;
    public string $title;
    public string $info;
    public string $place;
    public int $costs;
    public int $min_age;
    public int $max_age;
    public int $min_participants;
    public int $max_participants;
    public string $presentation_type;
    public string $requirements;
    public bool $random_assignments;

    protected static $insert_stmt = null;
    protected static $update_stmt = null;

    protected static function getInsertStatement() {
        global $db;
        if (null === self::$insert_stmt) {
            self::$insert_stmt = $stmt = $db->prepare('INSERT INTO projects (title, info, place, costs, min_grade, max_grade, min_participants, max_participants, presentation_type, requirements, random_assignments) VALUES (:title, :info, :place, :costs, :min_grade, :max_grade, :min_participants, :max_participants, :presentation_type, :requirements, :random_assignments)');
        }
        return self::$insert_stmt;
    }

    protected static function getUpdateStatement() {
        global $db;
        if (null === self::$update_stmt) {
            self::$update_stmt = $db->prepare('UPDATE projects SET title = :title, info = :info, place = :place, costs = :costs, min_grade = :min_grade, max_grade = :max_grade, min_participants = :min_participants, max_participants = :max_participants, presentation_type = :presentation_type, requirements = :requirements, random_assignments = :random_assignments WHERE id = :id');
        }
        return self::$update_stmt;
    }
}

?>