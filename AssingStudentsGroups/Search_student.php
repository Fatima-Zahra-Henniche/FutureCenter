<?php
include '../db_config.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Base query
$query = "SELECT id, id_etudiant, nom, prenom, date_naissance FROM etudiants";

// Apply search filter
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " WHERE 
        id_etudiant LIKE '%$search%' OR
        nom LIKE '%$search%' OR
        prenom LIKE '%$search%' OR
        CONCAT(nom, ' ', prenom) LIKE '%$search%'";
}

$query .= " ORDER BY id DESC";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <div class="student-card" data-student-id="<?= htmlspecialchars($row['id']) ?>">
            <div class="student-id">#<?= htmlspecialchars($row['id_etudiant']) ?></div>
            <div class="student-name"><?= htmlspecialchars($row['nom'] . ' ' . $row['prenom']) ?></div>
            <div class="student-birth"><?= htmlspecialchars($row['date_naissance']) ?></div>
        </div>
<?php
    }
} else {
    echo "<p class='no-results'>لا توجد نتائج مطابقة.</p>";
}

$conn->close();
?>