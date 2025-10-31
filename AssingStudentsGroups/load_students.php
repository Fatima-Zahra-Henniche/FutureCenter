<?php
$conn = new mysqli("localhost", "root", "", "gestion_ecole");
if ($conn->connect_error) die("DB Error");

$search = isset($_GET['search']) ? $_GET['search'] : '';
$search = "%$search%";
$stmt = $conn->prepare("SELECT * FROM etudiants WHERE nom LIKE ? OR prenom LIKE ? ORDER BY nom");
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$res = $stmt->get_result();

while ($r = $res->fetch_assoc()) {
    echo "<div class='student'><input type='checkbox' value='{$r['id']}'> {$r['nom']} {$r['prenom']}</div>";
}
$conn->close();
