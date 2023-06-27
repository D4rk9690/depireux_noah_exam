<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["user_id"])) {
  $response = array(
    "status" => "error",
    "message" => "Utilisateur non connecté"
  );
  header("Content-Type: application/json");
  echo json_encode($response);
  exit;
}

// Inclure les fichiers nécessaires
require_once("config.php");
require_once("sql.php");

$dbhost = "localhost";
$dbname = "noah_depireux_examen";
$dbuser = "root";
$dbpass = "";
$dbprefix = "noah"; // Replace "mon_prefixe" with your desired prefix

// Create a new MySQLi instance
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check for connection errors
if ($mysqli->connect_errno) {
  $response = array(
    "status" => "error",
    "message" => "Erreur de connexion à la base de données"
  );
  header("Content-Type: application/json");
  echo json_encode($response);
  exit;
}

// Define the dbQuery() function
function dbQuery($query, $params = array()) {
  global $mysqli;
  $stmt = $mysqli->prepare($query);

  if (!$stmt) {
    $response = array(
      "status" => "error",
      "message" => "Erreur de requête à la base de données: " . $mysqli->error
    );
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
  }

  if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  return $result;
}

// Récupérer l'ID de l'utilisateur connecté
$userID = $_SESSION["user_id"];

// Récupérer les informations de l'utilisateur depuis la base de données
$query = "SELECT nom, prenom, credits FROM " . $dbprefix . "_utilisateurs WHERE ID = ?";
$params = array($userID);
$result = dbQuery($query, $params);

if ($result && $result->num_rows > 0) {
  $user = $result->fetch_assoc();
  $response = array(
    "status" => "success",
    "data" => array(
      "username" => $user["nom"],
      "firstname" => $user["prenom"],
      "credits" => $user["credits"]
    )
  );
} else {
  $response = array(
    "status" => "error",
    "message" => "Impossible de récupérer les informations de l'utilisateur"
  );
}

header("Content-Type: application/json");
echo json_encode($response);
