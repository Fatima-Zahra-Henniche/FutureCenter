<?php
$conn = new mysqli("localhost", "root", "", "gestion_ecole");
if ($conn->connect_error) die("DB Error");

$groups = [];
$result = $conn->query("SELECT * FROM groupes ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
}
$conn->close();
