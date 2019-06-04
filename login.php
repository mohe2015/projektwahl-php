<?php
$allowed_users = array();
require_once __DIR__ . '/head.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $password = $_POST['password'];
  $stmt = $db->prepare('SELECT * FROM users WHERE name = :name');
  $stmt->execute(array('name' => $name));
  $result = $stmt->fetchAll();
  if (password_verify($password, $result[0]["password"])) {
    if (password_needs_rehash($result[0]["password"], PASSWORD_DEFAULT, $options)) {
      // TODO: needs rehashing
    }
    session_regenerate_id(true);
    $_SESSION['id'] = $result[0]["id"];
    $_SESSION['name'] = $result[0]["name"];
    $_SESSION['type'] = $result[0]["type"];
    if ($result[0]["type"] === "student") {
      header("Location: /election.php");
    } else {
      header("Location: /");
    }
    die();
  } else {
    echo "invalid password";
  }
}
?>
<form method="post">
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" autofocus />
  <label for="password">Passwort:</label>
  <input type="password" id="password" name="password" />
  <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
  <button type="submit">Anmelden</button>
</form>
