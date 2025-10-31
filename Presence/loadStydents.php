<?php
include "db.php";
$gid = intval($_GET['gid'] ?? 0);
$sql = "SELECT e.id, e.nom, e.prenom
        FROM etudiants e
        JOIN etudiants_groupes eg ON e.id = eg.etudiant_id
        WHERE eg.groupe_id=$gid AND e.actif=1
        ORDER BY e.nom, e.prenom";
$res = $conn->query($sql);
$data = [];
while ($r = $res->fetch_assoc()) $data[] = $r;
echo json_encode($data, JSON_UNESCAPED_UNICODE);
$conn->close();
