<?php
include '../db_config.php';

$search = trim($_GET['search'] ?? '');
$exact_match = isset($_GET['exact_match']);
$niveau_filter = $_GET['niveau_filter'] ?? 'جميع المستويات';

$query = "
SELECT e.id, e.id_etudiant, e.nom, e.prenom, e.date_naissance, e.niveau, e.telephone, e.email, e.nom_parent,
       GROUP_CONCAT(g.nom SEPARATOR ', ') AS groupes
FROM etudiants e
LEFT JOIN etudiants_groupes eg ON e.id = eg.etudiant_id
LEFT JOIN groupes g ON eg.groupe_id = g.id
WHERE e.actif = 1
";

$params = [];
$types = "";

// -------------------------------------
// 🔍 شروط البحث المركّبة
// -------------------------------------
if (!empty($search)) {
    $operator = $exact_match ? "=" : "LIKE";
    $pattern = $exact_match ? $search : "%$search%";

    $query .= " AND ( 
        e.id_etudiant $operator ? 
        OR e.nom $operator ? 
        OR e.prenom $operator ? 
        OR CONCAT(e.nom, ' ', e.prenom) $operator ? 
        OR CONCAT(e.prenom, ' ', e.nom) $operator ?
        OR CONCAT(e.id_etudiant, ' ', e.nom) $operator ?
        OR CONCAT(e.id_etudiant, ' ', e.prenom) $operator ?
        OR CONCAT(e.id_etudiant, ' ', e.prenom, ' ', e.nom) $operator ?
        OR CONCAT(e.id_etudiant, ' ', e.nom, ' ', e.prenom) $operator ?
        OR CONCAT(e.nom, ' ', e.prenom, ' ', e.date_naissance) $operator ?
        OR CONCAT(e.prenom, ' ', e.nom, ' ', e.date_naissance) $operator ?
        OR CONCAT(e.id_etudiant, ' ', e.date_naissance) $operator ?
    )";

    $params = [
        $pattern, // id
        $pattern, // nom
        $pattern, // prenom
        $pattern, // nom + prenom
        $pattern, // prenom + nom
        $pattern, // id + nom
        $pattern, // id + prenom
        $pattern, // id + prenom + nom
        $pattern, // id + nom + prenom
        $pattern, // nom + prenom + date_naissance
        $pattern, // prenom + nom + date_naissance
        $pattern  // id + date_naissance        
    ];
    $types = str_repeat("s", count($params));
}

// -------------------------------------
// 🎓 فلترة حسب المستوى
// -------------------------------------
if ($niveau_filter != "جميع المستويات") {
    $query .= " AND e.niveau = ?";
    $params[] = $niveau_filter;
    $types .= "s";
}

$query .= " GROUP BY e.id ORDER BY e.nom, e.prenom";

// -------------------------------------
// ⚙️ تنفيذ الاستعلام
// -------------------------------------
$stmt = $conn->prepare($query);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$total_students = $result->num_rows;
?>

<!-- ✅ عرض النتائج -->
<div class="results-info">
    <div class="results-stats">
        <span><i class="fas fa-users"></i> <strong>عدد الطلاب:</strong> <?= $total_students ?></span>
        <?php if ($search): ?>
            <span><i class="fas fa-search"></i> <strong>بحث عن:</strong> "<?= htmlspecialchars($search) ?>"</span>
        <?php endif; ?>
        <?php if ($niveau_filter != "جميع المستويات"): ?>
            <span><i class="fas fa-filter"></i> <strong>المستوى:</strong> <?= htmlspecialchars($niveau_filter) ?></span>
        <?php endif; ?>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>رقم التسجيل</th>
                <th>اللقب</th>
                <th>الاسم</th>
                <th>تاريخ الميلاد</th>
                <th>المستوى</th>
                <th>الأفواج</th>
                <th>الرصيد</th>
                <th>الهاتف</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($total_students > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id_etudiant']) ?></td>
                        <td><?= htmlspecialchars($row['prenom']) ?></td>
                        <td><?= htmlspecialchars($row['nom']) ?></td>
                        <td><?= htmlspecialchars($row['date_naissance']) ?></td>
                        <td><span class="level-badge"><?= htmlspecialchars($row['niveau']) ?></span></td>
                        <td><?= htmlspecialchars($row['groupes'] ?? '-') ?></td>
                        <td class="balance-cell" data-id="<?= $row['id'] ?>">
                            <div class="balance-loading"><i class="fas fa-spinner fa-spin"></i></div>
                        </td>
                        <td><?= htmlspecialchars($row['telephone']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="EditStudent.php?id=<?= $row['id'] ?>" class="btn btn-edit"><i class="fas fa-edit"></i></a>
                                <a href="DeleteStudent.php?id=<?= $row['id'] ?>" class="btn btn-delete"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا الطالب؟')"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="no-data">لا توجد نتائج مطابقة</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>