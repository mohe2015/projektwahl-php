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
$allowed_users = array("student", "teacher", "admin");
require_once __DIR__ . '/header.php';

$user = end($_SESSION['users']);

require_once __DIR__ . '/vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;

$userData = explode(" ", $user->name);
array_push($userData, $user->name);

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($_POST['new_password'], $userData);
echo json_encode($strength);
?>
