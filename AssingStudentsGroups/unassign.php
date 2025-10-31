<?php
$data = json_decode(file_get_contents("php://input"), true);
$conn = new mysqli("localhost", "root", "", "gestion_ecole");

$count = 0;
foreach ($data['ids'] as $id) {
    $stmt = $conn->prepare("DELETE FROM etudiants_groupes WHERE etudiant_id=? AND groupe_id=?");
    $stmt->bind_param("ii", $id, $data['groupId']);
    $stmt->execute();
    $count++;
}
$conn->close();
echo "تم إلغاء انتساب $count طالب/ـة من الفوج.";
