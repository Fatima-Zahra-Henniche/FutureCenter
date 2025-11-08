<?php
include "../db_config.php";

header('Content-Type: application/json; charset=utf-8');

try {
    // Get POSTed data
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("No data received");
    }

    $gid = intval($data['gid'] ?? 0);
    $date = $conn->real_escape_string($data['date'] ?? '');
    $timeStart = $conn->real_escape_string($data['timeStart'] ?? '');
    $timeEnd = $conn->real_escape_string($data['timeEnd'] ?? '');
    $checked = $data['checked'] ?? [];

    if (!$gid || !$date) {
        throw new Exception("Missing required fields");
    }

    // Get group price
    $groupPriceQuery = $conn->query("SELECT prix_seance FROM groupes WHERE id = $gid");
    $groupPrice = $groupPriceQuery->fetch_assoc()['prix_seance'] ?? 100;

    // Begin transaction
    $conn->begin_transaction();

    // Fetch students of this group
    $sql = "SELECT e.id, e.nom, e.prenom, COALESCE(e.balance, 0) as balance 
            FROM etudiants e
            JOIN etudiants_groupes eg ON e.id = eg.etudiant_id
            WHERE eg.groupe_id = $gid AND e.actif=1
            ORDER BY e.nom, e.prenom";

    $res = $conn->query($sql);

    if (!$res) {
        throw new Exception("Error fetching students: " . $conn->error);
    }

    $report = [];

    while ($r = $res->fetch_assoc()) {
        $studentId = $r['id'];
        $before = floatval($r['balance']);
        $status = in_array($studentId, $checked) ? 'Present' : 'Absent'; // Match your database enum

        // Update balance only if present
        $after = $before;
        if ($status === 'Present') {
            $after = $before - $groupPrice;

            // Update student balance
            $updateSql = "UPDATE etudiants SET balance = $after WHERE id = $studentId";
            if (!$conn->query($updateSql)) {
                throw new Exception("Error updating balance: " . $conn->error);
            }
        }

        // Insert attendance record - match your table structure
        $insertSql = "INSERT INTO presence (etudiant_id, groupe_id, date, time_start, time_end, statut, montant_debite) 
                      VALUES ($studentId, $gid, '$date', '$timeStart', '$timeEnd', '$status', " . ($status === 'Present' ? $groupPrice : 0) . ")";

        if (!$conn->query($insertSql)) {
            throw new Exception("Error inserting attendance: " . $conn->error);
        }

        $report[] = [
            'nom' => $r['nom'],
            'prenom' => $r['prenom'],
            'status' => $status,
            'before' => $before,
            'after' => $after
        ];
    }

    $conn->commit();
    echo json_encode($report, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
