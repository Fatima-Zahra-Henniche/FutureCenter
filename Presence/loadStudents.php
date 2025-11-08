<?php
include "../db_config.php";

header('Content-Type: application/json; charset=utf-8');

$gid = intval($_GET['gid'] ?? 0);

if (!$gid) {
        echo json_encode([]);
        exit;
}

$sql = "SELECT e.id, e.nom, e.prenom, COALESCE(e.balance, 0) as balance
        FROM etudiants e
        JOIN etudiants_groupes eg ON e.id = eg.etudiant_id
        WHERE eg.groupe_id = $gid AND e.actif = 1
        ORDER BY e.nom, e.prenom";

$res = $conn->query($sql);

$data = [];
if ($res) {
        while ($r = $res->fetch_assoc()) {
                $data[] = $r;
        }
} else {
        error_log("SQL Error: " . $conn->error);
}

echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conn->close();
