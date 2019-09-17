<?php
require_once __DIR__ . 'vendor/autoload.php';
use ZxcvbnPhp\Zxcvbn;

// TODO FIXME
$userData = [
  'Marco',
  'marco@example.com'
];

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($_SERVER['QUERY_STRING'], $userData);
echo json_encode($strength);
?>
