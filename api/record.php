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

    public function __construct(?array $data) {
        if ($data !== null) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->new = $data !== null;
    }

    public function save() {
        $reflect = new ReflectionClass($this);
        $props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $props_arr = array();
        foreach ($props as $prop) {
            $props_arr[$prop->getName()] = $prop->getValue($this); 
        }
        if ($this->new) {
            self::getInsertStatement()->execute($props_arr);
            if (property_exists($this, 'id')) {
                $this->id = $db->lastInsertId();
            }
            $this->new = false;
        } else {
            self::getUpdateStatement()->execute($props_arr);
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
    public function find($session_id): Session {
        global $db;
        $stmt = $db->prepare("SELECT * FROM sessions WHERE session_id = :session_id");
        $stmt->execute(array('session_id' => $session_id));
        $result = $stmt->fetchObject('Session');
        $result->new = false;
        return $result;
    }
}

class User extends Record {
    public ?int $id;
    public string $name;
    public string $password_hash;
    public string $type;
    public bool $password_changed;

    public ?int $project_leader;
    public ?string $class;
    public ?int $age;
    public ?bool $away;
    public ?int $in_project;

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
}

class Users {
    public function find($id) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(array('id' => $id));
        $result = $stmt->fetchObject('User', array(null));
        $result->new = false;
        return $result;
    }

    public function findByName($name) {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute(array('name' => $name));
        $result = $stmt->fetchObject('User', array(null));
        $result->new = false;
        return $result;
    }

    public function all() {
        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE type = 'teacher' OR type = 'student';");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
        $result->new = false;
        return $result;
    }
}

?>