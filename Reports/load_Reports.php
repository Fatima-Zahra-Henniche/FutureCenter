<?php
header("Content-Type: application/json");
require_once("../db_config.php");

$dateFrom = $_GET['from'] ?? '2000-01-01';
$dateTo = $_GET['to'] ?? date('Y-m-d');
$search = trim($_GET['search'] ?? '');
$exact = isset($_GET['exact']) && $_GET['exact'] === 'true';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) die(json_encode(["error" => "DB Connection Failed"]));

function pattern($v, $exact)
{
    return $exact ? $v : "%$v%";
}

$where = "1";
$params = [];
$types = "";

if ($search !== "") {
    $op = $exact ? "=" : "LIKE";
    $p = pattern($search, $exact);
    $where = "(e.id_etudiant $op ? OR e.nom $op ? OR e.prenom $op ? 
              OR CONCAT(e.nom, ' ', e.prenom) $op ? OR CONCAT(e.prenom, ' ', e.nom) $op ?)";
    $params = [$p, $p, $p, $p, $p];
    $types = str_repeat("s", count($params));
}

// --- Attendance ---
$sql1 = "SELECT p.date, g.nom AS groupe, e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS etudiant,
         p.time_start, p.time_end, p.statut, p.montant_debite, p.notes
         FROM presence p
         JOIN etudiants e ON p.etudiant_id = e.id
         JOIN groupes g ON p.groupe_id = g.id
         WHERE $where AND p.date BETWEEN ? AND ? ORDER BY p.date DESC";
$stmt = $conn->prepare($sql1);
if ($search !== "") $stmt->bind_param($types . "ss", ...$params, $dateFrom, $dateTo);
else $stmt->bind_param("ss", $dateFrom, $dateTo);
$stmt->execute();
$attendance = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// --- Payments ---
$sql2 = "SELECT p.date_paiement, e.id_etudiant, CONCAT(e.nom,' ',e.prenom) AS etudiant,
         p.montant, p.type_paiement, p.notes
         FROM paiements p
         JOIN etudiants e ON p.etudiant_id = e.id
         WHERE $where AND p.date_paiement BETWEEN ? AND ? ORDER BY p.date_paiement DESC";
$stmt = $conn->prepare($sql2);
if ($search !== "") $stmt->bind_param($types . "ss", ...$params, $dateFrom, $dateTo);
else $stmt->bind_param("ss", $dateFrom, $dateTo);
$stmt->execute();
$payments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// --- Students ---
$sql3 = "SELECT e.id_etudiant, e.nom, e.prenom, e.niveau, e.date_inscription,
         COUNT(DISTINCT eg.groupe_id) AS groupes,
         COALESCE((SELECT SUM(montant) FROM paiements WHERE etudiant_id=e.id),0)
         - COALESCE((SELECT SUM(montant_debite) FROM presence WHERE etudiant_id=e.id),0) AS balance
         FROM etudiants e
         LEFT JOIN etudiants_groupes eg ON e.id=eg.etudiant_id
         WHERE e.actif=1 AND $where
         GROUP BY e.id ORDER BY e.date_inscription DESC";
$stmt = $conn->prepare($sql3);
if ($search !== "") $stmt->bind_param($types, ...$params);
$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "attendance" => $attendance,
    "payments" => $payments,
    "students" => $students
], JSON_UNESCAPED_UNICODE);
$conn->close();
