<?php
header("Content-Type: application/json");
require_once("../../db_config.php");

$sql = "SELECT id_etudiant, prenom, nom, niveau, date_inscription
        FROM etudiants
        WHERE actif=1
        ORDER BY date_inscription DESC
        LIMIT 10";
$res = $conn->query($sql);

$rows = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
}

echo json_encode($rows);
$conn->close();
