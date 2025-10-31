<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

include "../db_config.php";

$action = $_GET['action'] ?? '';

switch ($action) {
    case "list":
        list_groups($conn);
        break;
    case "add":
        add_group($conn);
        break;
    case "edit":
        edit_group($conn);
        break;
    case "delete":
        delete_group($conn);
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
}

$conn->close();

function list_groups($conn)
{
    $sql = "SELECT * FROM groupes WHERE actif=1 ORDER BY id DESC";
    $result = $conn->query($sql);
    $groups = [];
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }
    echo json_encode($groups);
}

function add_group($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $nom = $data["nom"];
    $prix = $data["prix"];
    $capacite = $data["capacite"];

    $stmt = $conn->prepare("INSERT INTO groupes (nom, prix_seance, capacite_max) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $nom, $prix, $capacite);
    $stmt->execute();

    echo json_encode(["success" => true, "id" => $stmt->insert_id]);
}

function edit_group($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data["id"];
    $nom = $data["nom"];
    $prix = $data["prix"];
    $capacite = $data["capacite"];

    $stmt = $conn->prepare("UPDATE groupes SET nom=?, prix_seance=?, capacite_max=? WHERE id=?");
    $stmt->bind_param("sdii", $nom, $prix, $capacite, $id);
    $stmt->execute();

    echo json_encode(["success" => true]);
}

function delete_group($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data["id"];

    $stmt = $conn->prepare("UPDATE groupes SET actif=0 WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(["success" => true]);
}
