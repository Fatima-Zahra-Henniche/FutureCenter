<?php
header("Content-Type: application/json");
require_once("../../db_config.php");

$sql = "
SELECT g.nom, g.prix_seance, COUNT(eg.etudiant_id) AS students_count
FROM groupes g
LEFT JOIN etudiants_groupes eg ON g.id = eg.groupe_id
WHERE g.actif=1
GROUP BY g.id
ORDER BY g.nom ASC
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
