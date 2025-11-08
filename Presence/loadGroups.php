<?php
include "../db_config.php";

$res = $conn->query("SELECT id, nom FROM groupes WHERE actif=1 ORDER BY nom");
$data = [];
while ($r = $res->fetch_assoc()) {
    $data[] = $r;
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);

$conn->close();
