<?php
include "db.php";
$data = json_decode(file_get_contents("php://input"), true);

$gid = intval($data["gid"]);
$date = $data["date"];
$tstart = $data["timeStart"];
$tend = $data["timeEnd"];
$checked = $data["checked"];

$report = [];

// Get price of session
$res = $conn->query("SELECT prix_seance FROM groupes WHERE id=$gid");
$row = $res->fetch_assoc();
$prix = $row ? floatval($row["prix_seance"]) : 0;

foreach ($checked as $sid) {
    $sid = intval($sid);

    // Get current balance
    $res2 = $conn->query("SELECT solde, nom, prenom FROM etudiants WHERE id=$sid");
    $st = $res2->fetch_assoc();
    $before = floatval($st["solde"]);

    // Deduct price
    $after = $before - $prix;
    if ($after < 0) $after = 0;

    // Update balance
    $conn->query("UPDATE etudiants SET solde=$after WHERE id=$sid");

    // Insert presence
    $conn->query("INSERT INTO presence 
    (etudiant_id, groupe_id, date, time_start, time_end, statut, montant_debite)
    VALUES ($sid, $gid, '$date', '$tstart', '$tend', 'Present', $prix)");

    $report[] = [
        "nom" => $st["nom"],
        "prenom" => $st["prenom"],
        "status" => "حاضر",
        "before" => $before,
        "after" => $after
    ];
}

// Absent students (not checked)
$res3 = $conn->query("SELECT e.id, e.nom, e.prenom, e.solde
                      FROM etudiants e
                      JOIN etudiants_groupes eg ON e.id=eg.etudiant_id
                      WHERE eg.groupe_id=$gid AND e.actif=1");
while ($s = $res3->fetch_assoc()) {
    if (!in_array($s["id"], $checked)) {
        $conn->query("INSERT INTO presence 
      (etudiant_id, groupe_id, date, time_start, time_end, statut, montant_debite)
      VALUES ({$s['id']}, $gid, '$date', '$tstart', '$tend', 'Absent', 0)");
        $report[] = [
            "nom" => $s["nom"],
            "prenom" => $s["prenom"],
            "status" => "غائب",
            "before" => $s["solde"],
            "after" => $s["solde"]
        ];
    }
}

echo json_encode($report, JSON_UNESCAPED_UNICODE);
$conn->close();
