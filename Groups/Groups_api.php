<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
include "../db_config.php";

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        list_groups($conn);
        break;
    case 'add':
        add_group($conn);
        break;
    case 'edit':
        edit_group($conn);
        break;
    case 'delete':
        delete_group($conn);
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
}

$conn->close();

function list_groups($conn)
{
    $sql = "
    SELECT 
      g.id,
      g.nom,
      g.prix_seance,
      g.capacite_max,
      COUNT(eg.id) AS nombre_etudiants
    FROM groupes g
    LEFT JOIN etudiants_groupes eg ON g.id = eg.groupe_id
    WHERE g.actif = 1
    GROUP BY g.id, g.nom, g.prix_seance, g.capacite_max
    ORDER BY g.id DESC
  ";
    $res = $conn->query($sql);
    $rows = [];
    while ($row = $res->fetch_assoc()) $rows[] = $row;
    echo json_encode($rows);
}

function add_group($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("INSERT INTO groupes (nom, prix_seance, capacite_max, actif) VALUES (?, ?, ?, 1)");
    $stmt->bind_param("sdi", $data["nom"], $data["prix"], $data["capacite"]);
    $stmt->execute();
    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
}

function edit_group($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("UPDATE groupes SET nom=?, prix_seance=?, capacite_max=? WHERE id=?");
    $stmt->bind_param("sdii", $data["nom"], $data["prix"], $data["capacite"], $data["id"]);
    $stmt->execute();
    echo json_encode(["success" => true]);
}

function delete_group($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $conn->prepare("UPDATE groupes SET actif=0 WHERE id=?");
    $stmt->bind_param("i", $data["id"]);
    $stmt->execute();
    echo json_encode(["success" => true]);
}
