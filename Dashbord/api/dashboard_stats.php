<?php
header("Content-Type: application/json");
require_once("../../db_config.php");

// Total students
$res1 = $conn->query("SELECT COUNT(*) AS c FROM etudiants WHERE actif=1");
$students = ($res1) ? (int)$res1->fetch_assoc()['c'] : 0;

// Total groups
$res2 = $conn->query("SELECT COUNT(*) AS c FROM groupes WHERE actif=1");
$groups = ($res2) ? (int)$res2->fetch_assoc()['c'] : 0;

// Optional: total payments
$res3 = $conn->query("SELECT SUM(montant) AS s FROM paiements");
$payments_sum = ($res3) ? (float)$res3->fetch_assoc()['s'] : 0;

echo json_encode([
    "students" => $students,
    "groups" => $groups,
    "payments_sum" => $payments_sum
]);

$conn->close();
