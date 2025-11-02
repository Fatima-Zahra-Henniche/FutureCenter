<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once("../db_config.php");

$dateFrom = $_GET['from'] ?? '2000-01-01';
$dateTo = $_GET['to'] ?? date('Y-m-d');
$search = $_GET['search'] ?? '';
$exact = isset($_GET['exact']) && $_GET['exact'] === 'true';

$search = $conn->real_escape_string($search);

// Build search condition
$searchCond = "";
if ($search !== '') {
    if ($exact) {
        $searchCond = "AND (student_name = '$search' OR student_id = '$search')";
    } else {
        $searchCond = "AND (student_name LIKE '%$search%' OR student_id LIKE '%$search%')";
    }
}

// Attendance
$q1 = "SELECT date, group_name, student_id, student_name, start_time, end_time, status, penalty, note
        FROM attendance
        WHERE date BETWEEN '$dateFrom' AND '$dateTo' $searchCond";
$result1 = $conn->query($q1);
if (!$result1) {
    echo json_encode(["error" => $conn->error]);
    exit;
}
$attendance = $result1->fetch_all(MYSQLI_NUM);

// Payments
$q2 = "SELECT date, student_id, student_name, amount, method, note
        FROM payments
        WHERE date BETWEEN '$dateFrom' AND '$dateTo' $searchCond";
$result2 = $conn->query($q2);
if (!$result2) {
    echo json_encode(["error" => $conn->error]);
    exit;
}
$payments = $result2->fetch_all(MYSQLI_NUM);

// Students
$q3 = "SELECT student_id, first_name, last_name, level, registration_date, sessions_count, balance
        FROM students
        WHERE 1=1 $searchCond";
$result3 = $conn->query($q3);
if (!$result3) {
    echo json_encode(["error" => $conn->error]);
    exit;
}
$students = $result3->fetch_all(MYSQLI_NUM);

echo json_encode([
    "attendance" => $attendance,
    "payments" => $payments,
    "students" => $students
]);
