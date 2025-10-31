<?php
include '../db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM etudiants WHERE id = $id");
}

header("Location: index.php");
exit;
