<?php
$data = json_decode(file_get_contents("php://input"), true);
$conn = new mysqli("localhost", "root", "", "gestion_ecole");

$count = 0;
foreach ($data['ids'] as $id) {
    $stmt = $conn->prepare("INSERT IGNORE INTO etudiants_groupes (etudiant_id, groupe_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $id, $data['groupId']);
    $stmt->execute();
    $count++;
}
$conn->close();
echo "تم ربط $count طالب/ـة بالفوج.";
