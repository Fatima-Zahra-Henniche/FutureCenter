<?php
header("Content-Type: application/json; charset=UTF-8");
include "../db_config.php";

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'balance':
        get_balance($conn);
        break;
    default:
        echo json_encode(["error" => "Invalid action"]);
}

$conn->close();

function get_balance($conn)
{
    $id = intval($_GET['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(["error" => "Missing student ID"]);
        return;
    }

    // مجموع الدفعات
    $res1 = $conn->query("SELECT SUM(montant) AS total_payments FROM paiements WHERE etudiant_id = $id");
    $total_payments = $res1->fetch_assoc()["total_payments"] ?? 0;

    // مجموع المبالغ المخصومة
    $res2 = $conn->query("SELECT SUM(montant_debite) AS total_debited FROM presence WHERE etudiant_id = $id");
    $total_debited = $res2->fetch_assoc()["total_debited"] ?? 0;

    $balance = floatval($total_payments) - floatval($total_debited);
    echo json_encode(["id" => $id, "balance" => $balance]);
}
