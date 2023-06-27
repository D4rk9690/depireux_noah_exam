<?php
// Inclure les fichiers nécessaires
require_once("config.php");


$dbhost = "localhost";
$dbname = "noah_depireux_examen";
$dbuser = "root";
$dbpass = "";
$dbprefix = "noah"; // Replace "mon_prefixe" with your desired prefix

// Create a new MySQLi instance
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check for connection errors
if ($mysqli->connect_errno) {
  die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Define the dbQuery() function
function dbQuery($query, $params = array()) {
  global $mysqli;
  $stmt = $mysqli->prepare($query);
  
  if (!$stmt) {
    die("Error in database query: " . $mysqli->error);
  }
  
  if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
  }
  
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  
  return $result;
}

// Vérifier si l'action est "login"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "login") {
  // Récupérer les données envoyées depuis le front-end
  $email = $_POST["email"];
  $password = $_POST["password"];

  // Vérifier les informations de connexion en DB
  $user = getUserByEmailAndPassword($email, $password);

  if ($user !== null) {
    // Créer une session pour l'utilisateur
    session_start();
    $_SESSION["user_id"] = $user["ID"];
    $_SESSION["username"] = $user["nom"];
    $_SESSION["firstname"] = $user["prenom"];
    // TODO: Récupérer le montant de crédits disponibles pour l'utilisateur

    // Préparer la réponse avec les données de l'utilisateur
    $response = array(
      "status" => "success",
      "message" => "Connexion réussie",
      "data" => array(
        "username" => $user["nom"],
        "firstname" => $user["prenom"],
        "credits" => 0 // Montant de crédits disponibles à récupérer depuis la DB
      )
    );
  } else {
    // Informations de connexion incorrectes
    $response = array(
      "status" => "error",
      "message" => "Adresse email ou mot de passe incorrect"
    );
  }

  // Renvoyer la réponse encodée en JSON
  header("Content-Type: application/json");
  echo json_encode($response);
}

// Fonction pour vérifier les informations de connexion en DB
function getUserByEmailAndPassword($email, $password) {
  global $dbprefix;

  $hashedPassword = hash("sha256", $password);
  $query = "SELECT * FROM " . $dbprefix . "_utilisateurs WHERE email = ? AND mdp = ?";
  $params = array($email, $hashedPassword);
  $result = dbQuery($query, $params);

  if ($result && $result->num_rows > 0) {
    // Utilisateur trouvé, retourner les données
    return $result->fetch_assoc();
  } else {
    // Utilisateur non trouvé
    return null;
  }
}
