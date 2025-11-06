<?php
include '../db_config.php';

// -------------------------
// Prepare search/query logic
// -------------------------
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$exact_match = isset($_GET['exact_match']) ? true : false;
$niveau_filter = isset($_GET['niveau_filter']) ? $_GET['niveau_filter'] : 'جميع المستويات';

// Build the query
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

if (!empty($search)) {
    $operator = $exact_match ? "=" : "LIKE";

    if (!function_exists('pattern')) {
        function pattern($value, $exact)
        {
            return $exact ? $value : "%$value%";
        }
    }

    $conditions = [];

    // 1️⃣ Direct ID search
    $conditions[] = "CAST(id_etudiant AS CHAR) $operator ?";
    $params[] = pattern($search, $exact_match);
    $types .= "s";

    // 2️⃣ Search on nom, prenom alone
    $conditions[] = "nom $operator ?";
    $conditions[] = "prenom $operator ?";
    $params[] = pattern($search, $exact_match);
    $params[] = pattern($search, $exact_match);
    $types .= "ss";

    // 3️⃣ Search on nom+prenom / prenom+nom combinations
    $conditions[] = "CONCAT(nom, ' ', prenom) $operator ?";
    $conditions[] = "CONCAT(prenom, ' ', nom) $operator ?";
    $params[] = pattern($search, $exact_match);
    $params[] = pattern($search, $exact_match);
    $types .= "ss";

    // 4️⃣ Search with date_naissance
    $conditions[] = "CONCAT(nom, ' ', prenom, ' ', date_naissance) $operator ?";
    $conditions[] = "CONCAT(prenom, ' ', nom, ' ', date_naissance) $operator ?";
    $conditions[] = "CONCAT(nom, ' ', date_naissance) $operator ?";
    $conditions[] = "CONCAT(prenom, ' ', date_naissance) $operator ?";
    $params[] = pattern($search, $exact_match);
    $params[] = pattern($search, $exact_match);
    $params[] = pattern($search, $exact_match);
    $params[] = pattern($search, $exact_match);
    $types .= "ssss";

    // 5️⃣ If multiple words, generate smart combinations
    $words = explode(' ', $search);
    if (count($words) >= 2) {
        $full_search = implode(' ', $words);
        $conditions[] = "CONCAT(nom, ' ', prenom) $operator ?";
        $conditions[] = "CONCAT(prenom, ' ', nom) $operator ?";
        $params[] = pattern($full_search, $exact_match);
        $params[] = pattern($full_search, $exact_match);
        $types .= "ss";
    }

    if (count($words) >= 3) {
        $full_search = implode(' ', $words);
        $conditions[] = "CONCAT(nom, ' ', prenom, ' ', date_naissance) $operator ?";
        $conditions[] = "CONCAT(prenom, ' ', nom, ' ', date_naissance) $operator ?";
        $params[] = pattern($full_search, $exact_match);
        $params[] = pattern($full_search, $exact_match);
        $types .= "ss";
    }

    $query .= " AND (" . implode(" OR ", $conditions) . ")";
}

if ($niveau_filter != "جميع المستويات") {
    $query .= " AND niveau = ?";
    $params[] = $niveau_filter;
    $types .= "s";
}

$query .= " GROUP BY e.id ORDER BY e.nom, e.prenom";

// Prepare and execute
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$total_students = $result->num_rows;

// If AJAX request, return only the fragment (results-info + table) and exit
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
?>
    <div class="results-info">
        <div class="results-stats">
            <span class="stat-item">
                <i class="fas fa-users"></i>
                <strong>عدد الطلاب:</strong> <?= $total_students ?> طالب
            </span>
            <?php if (!empty($search)): ?>
                <span class="stat-item">
                    <i class="fas fa-search"></i>
                    <strong>نتائج البحث عن:</strong> "<?= htmlspecialchars($search) ?>"
                </span>
            <?php endif; ?>
            <?php if ($niveau_filter != "جميع المستويات"): ?>
                <span class="stat-item">
                    <i class="fas fa-filter"></i>
                    <strong>المستوى:</strong> <?= htmlspecialchars($niveau_filter) ?>
                </span>
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
                            <td><?= htmlspecialchars($row['groupes']) ?></td>
                            <td class="balance-cell" data-id="<?= $row['id'] ?>">
                                <div class="balance-loading">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['telephone']) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="EditStudent.php?id=<?= $row['id'] ?>" class="btn btn-edit" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="DeleteStudent.php?id=<?= $row['id'] ?>" class="btn btn-delete" title="حذف"
                                        onclick="return confirm('هل أنت متأكد من حذف هذا الطالب من النظام ؟')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="no-data">
                            <div class="no-data-content">
                                <i class="fas fa-search fa-2x"></i>
                                <h3>
                                    <?php if (!empty($search) || $niveau_filter != "جميع المستويات"): ?>
                                        لم يتم العثور على طلاب مطابقين لمعايير البحث
                                    <?php else: ?>
                                        لا توجد طلاب مسجلين في النظام
                                    <?php endif; ?>
                                </h3>
                                <p>جرب تعديل معايير البحث أو أضف طلاب جدد</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلاب - Future Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #B77466;
            --primary-light: #E2B59A;
            --secondary: #957C62;
            --background: #FFF7EE;
            --white: #ffffff;
            --text-dark: #333333;
            --text-light: #666666;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            display: flex;
            direction: rtl;
            background: var(--background);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, var(--primary), var(--primary-light));
            color: var(--white);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding-top: 25px;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.15);
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
            transition: var(--transition);
        }

        .sidebar-header {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .sidebar-subtitle {
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 0 15px;
            flex-grow: 1;
        }

        .menu a {
            text-decoration: none;
            color: var(--white);
            padding: 12px 15px;
            border-radius: 8px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }

        .menu a i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(-5px);
        }

        .menu a.active {
            background: var(--secondary);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .logout-btn {
            margin-top: auto;
            background: rgba(211, 47, 47, 0.9) !important;
            border: none;
            font-family: inherit;
            font-size: 1rem;
        }

        .logout-btn:hover {
            background: rgba(211, 47, 47, 1) !important;
            transform: translateX(-5px);
        }

        /* Main Content */
        .content {
            flex: 1;
            margin-right: 260px;
            padding: 30px;
            box-sizing: border-box;
            transition: var(--transition);
        }

        /* Tabs */
        .tabs {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .tab-btn {
            background: var(--white);
            color: var(--primary);
            border: 2px solid var(--primary);
            padding: 12px 30px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow);
        }

        .tab-btn.active {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(183, 116, 102, 0.3);
        }

        .tab-btn:hover {
            background: var(--primary-light);
            color: var(--white);
            transform: translateY(-2px);
        }

        .tab-content {
            display: none;
            background: var(--white);
            padding: 30px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            animation: fadeIn 0.4s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Search Section */
        .search-nav {
            background: var(--white);
            padding: 25px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .search-input {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            text-align: right;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        /* Results Info */
        .results-info {
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: var(--white);
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .results-stats {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-item i {
            font-size: 1.1rem;
        }

        /* Add Student Form */
        #addTab form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 100%;
        }

        #addTab input,
        #addTab select,
        #addTab textarea {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            text-align: right;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
        }

        #addTab input:focus,
        #addTab select:focus,
        #addTab textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
        }

        #addTab textarea {
            grid-column: 1 / -1;
            min-height: 80px;
            resize: vertical;
        }

        #addTab button[type="submit"] {
            grid-column: 1 / -1;
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 10px;
        }

        #addTab button[type="submit"]:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(149, 124, 98, 0.3);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 15px;
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            min-width: 1000px;
        }

        th {
            background: var(--primary);
            color: var(--white);
            padding: 16px;
            font-weight: 600;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        tr:hover {
            background: #f5f5f5;
        }

        /* Level Badge */
        .level-badge {
            background: var(--primary-light);
            color: var(--text-dark);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Balance Cell */
        .balance-cell {
            font-weight: 600;
            min-width: 100px;
        }

        .balance-loading {
            color: var(--text-light);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn {
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: var(--transition);
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            min-width: 40px;
            height: 40px;
        }

        .btn-edit {
            background: #2980b9;
            color: #fff;
        }

        .btn-edit:hover {
            background: #3498db;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #c0392b;
            color: #fff;
        }

        .btn-delete:hover {
            background: #e74c3c;
            transform: translateY(-2px);
        }

        /* No Data State */
        .no-data {
            text-align: center;
            padding: 40px 20px !important;
        }

        .no-data-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            color: var(--text-light);
        }

        .no-data-content i {
            color: var(--primary-light);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .search-form {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }

            .content {
                margin-right: 220px;
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                padding-top: 20px;
                overflow: hidden;
            }

            .sidebar-header,
            .menu a span {
                display: none;
            }

            .menu a {
                justify-content: center;
                padding: 15px;
            }

            .menu a i {
                font-size: 1.3rem;
            }

            .content {
                margin-right: 70px;
                padding: 15px;
            }

            .tabs {
                flex-direction: column;
                gap: 10px;
            }

            .tab-btn {
                width: 100%;
                justify-content: center;
            }

            .results-stats {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding: 15px;
            }

            .sidebar-header,
            .menu a span {
                display: block;
            }

            .menu {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }

            .menu a {
                flex-direction: column;
                padding: 10px;
                font-size: 0.8rem;
                min-width: 70px;
            }

            .content {
                margin-right: 0;
                padding: 15px;
            }

            #addTab form {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Future Center</h2>
            <div class="sidebar-subtitle">نظام إدارة المدارس</div>
        </div>
        <nav class="menu">
            <a href="../Dashbord/Dashbord.php"><i class="fas fa-chart-line"></i> <span>الرئيسية</span></a>
            <a href="../Students/AddStudent.php" class="active"><i class="fas fa-user-graduate"></i> <span>الطلاب</span></a>
            <a href="../Groups/Groups.php"><i class="fas fa-users"></i> <span>الأفواج</span></a>
            <a href="../AssingStudentsGroups/index.php"><i class="fas fa-link"></i> <span>ربط الطلاب بالأفواج</span></a>
            <a href="../Presence/index.php"><i class="fas fa-clock"></i> <span>الحضور</span></a>
            <a href="../Reports/Report.php"><i class="fas fa-chart-bar"></i> <span>التقارير</span></a>
            <a href="../Login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span></a>
        </nav>
    </aside>

    <div class="content">
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab(event, 'listTab')">
                <i class="fas fa-list"></i> قائمة الطلاب
            </button>
            <button class="tab-btn" onclick="showTab(event, 'addTab')">
                <i class="fas fa-plus"></i> إضافة طالب
            </button>
        </div>

        <div id="addTab" class="tab-content">
            <form action="AddStudent.php" method="POST">
                <input type="text" name="id_etudiant" placeholder="رقم التسجيل" required>
                <input type="text" name="prenom" placeholder="اللقب" required>
                <input type="text" name="nom" placeholder="الاسم" required>
                <input type="date" name="date_naissance" required>
                <select name="niveau" required>
                    <option value="">اختر المستوى</option>
                    <option>الابتدائي</option>
                    <option>متوسط</option>
                    <option>الثانوي</option>
                    <option>بكالوريا</option>
                    <option>جامعي</option>
                </select>
                <input type="text" name="telephone" placeholder="الهاتف">
                <input type="email" name="email" placeholder="البريد الإلكتروني">
                <input type="text" name="nom_parent" placeholder="اسم ولي الأمر">
                <input type="text" name="tel_parent" placeholder="هاتف ولي الأمر">
                <textarea name="adresse" placeholder="العنوان"></textarea>
                <textarea name="notes" placeholder="ملاحظات"></textarea>
                <button type="submit">
                    <i class="fas fa-user-plus"></i> إضافة طالب
                </button>
            </form>
        </div>

        <div id="listTab" class="tab-content active">
            <div class="search-nav">
                <form method="GET" class="search-form" id="searchForm">
                    <div class="form-group">
                        <label for="search">بحث عن الطلاب:</label>
                        <input type="text" id="search" name="search" class="search-input"
                            placeholder="ابحث برقم التسجيل، الاسم، اللقب، تاريخ الميلاد..."
                            value="<?= htmlspecialchars($search) ?>">
                        <div class="checkbox-group">
                            <input type="checkbox" id="exact_match" name="exact_match" value="1"
                                <?= $exact_match ? 'checked' : '' ?>>
                            <label for="exact_match">بحث مطابق تماماً</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="niveau_filter">تصفية حسب المستوى:</label>
                        <select id="niveau_filter" name="niveau_filter" class="search-input">
                            <option value="جميع المستويات">جميع المستويات</option>
                            <option value="الابتدائي" <?= ($niveau_filter == 'الابتدائي') ? 'selected' : '' ?>>الابتدائي</option>
                            <option value="متوسط" <?= ($niveau_filter == 'متوسط') ? 'selected' : '' ?>>متوسط</option>
                            <option value="الثانوي" <?= ($niveau_filter == 'الثانوي') ? 'selected' : '' ?>>الثانوي</option>
                            <option value="بكالوريا" <?= ($niveau_filter == 'بكالوريا') ? 'selected' : '' ?>>بكالوريا</option>
                            <option value="جامعي" <?= ($niveau_filter == 'جامعي') ? 'selected' : '' ?>>جامعي</option>
                        </select>
                    </div>
                </form>

                <div id="searchResults">
                    <div class="results-info">
                        <div class="results-stats">
                            <span class="stat-item">
                                <i class="fas fa-users"></i>
                                <strong>عدد الطلاب:</strong> <?= $total_students ?> طالب
                            </span>
                            <?php if (!empty($search)): ?>
                                <span class="stat-item">
                                    <i class="fas fa-search"></i>
                                    <strong>نتائج البحث عن:</strong> "<?= htmlspecialchars($search) ?>"
                                </span>
                            <?php endif; ?>
                            <?php if ($niveau_filter != "جميع المستويات"): ?>
                                <span class="stat-item">
                                    <i class="fas fa-filter"></i>
                                    <strong>المستوى:</strong> <?= htmlspecialchars($niveau_filter) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
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
                                    <td><?= htmlspecialchars($row['groupes']) ?></td>
                                    <td class="balance-cell" data-id="<?= $row['id'] ?>">
                                        <div class="balance-loading">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['telephone']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="EditStudent.php?id=<?= $row['id'] ?>" class="btn btn-edit" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="DeleteStudent.php?id=<?= $row['id'] ?>" class="btn btn-delete" title="حذف"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الطالب من النظام ؟')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="no-data">
                                    <div class="no-data-content">
                                        <i class="fas fa-search fa-2x"></i>
                                        <h3>
                                            <?php if (!empty($search) || $niveau_filter != "جميع المستويات"): ?>
                                                لم يتم العثور على طلاب مطابقين لمعايير البحث
                                            <?php else: ?>
                                                لا توجد طلاب مسجلين في النظام
                                            <?php endif; ?>
                                        </h3>
                                        <p>جرب تعديل معايير البحث أو أضف طلاب جدد</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Load balances for all balance cells
        async function loadBalances() {
            const cells = document.querySelectorAll(".balance-cell");
            for (const cell of cells) {
                const id = cell.dataset.id;
                try {
                    const res = await fetch(`Student_api.php?action=balance&id=${id}`);
                    const data = await res.json();
                    if (data.balance !== undefined) {
                        const balance = Number(data.balance).toFixed(2);
                        cell.innerHTML = `<strong>${balance} دج</strong>`;
                        cell.style.color = balance < 0 ? "#e74c3c" : "#27ae60";
                    } else {
                        cell.innerHTML = '<strong>0.00 دج</strong>';
                        cell.style.color = "#666";
                    }
                } catch (e) {
                    cell.innerHTML = '<span style="color: #e74c3c;">خطأ</span>';
                    console.error('Balance fetch error:', e);
                }
            }
        }

        // Tab switching
        function showTab(event, tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.currentTarget.classList.add('active');
        }

        // Auto-search with AJAX
        function initAutoSearch() {
            const searchInput = document.getElementById("search");
            const niveauFilter = document.getElementById("niveau_filter");
            const exactMatch = document.getElementById("exact_match");
            const form = document.getElementById("searchForm");
            const listTab = document.getElementById("listTab");

            let timer;

            async function performSearch() {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                params.set('ajax', '1');

                try {
                    const res = await fetch('index.php?' + params.toString());
                    const html = await res.text();

                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Replace results-info
                    const newInfo = doc.querySelector('.results-info');
                    const oldInfo = document.querySelector('#searchResults .results-info');
                    if (newInfo && oldInfo) {
                        oldInfo.replaceWith(newInfo);
                    }

                    // Replace table container
                    const newTableContainer = doc.querySelector('.table-container');
                    const oldTableContainer = listTab.querySelector('.table-container');
                    if (newTableContainer && oldTableContainer) {
                        oldTableContainer.replaceWith(newTableContainer);
                        // Load balances for new table
                        await loadBalances();
                    }
                } catch (err) {
                    console.error('AJAX search error:', err);
                }
            }

            function delayedSearch() {
                clearTimeout(timer);
                timer = setTimeout(performSearch, 400);
            }

            searchInput.addEventListener('input', delayedSearch);
            niveauFilter.addEventListener('change', performSearch);
            exactMatch.addEventListener('change', performSearch);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadBalances();
            initAutoSearch();

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search') || urlParams.has('niveau_filter')) {
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.getElementById('listTab').classList.add('active');
                document.querySelector('.tab-btn:first-child').classList.add('active');
            }
        });
    </script>
</body>

</html>