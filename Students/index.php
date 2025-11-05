<?php
include '../db_config.php';

// -------------------------
// Prepare search/query logic (same logic you provided originally)
// -------------------------
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$exact_match = isset($_GET['exact_match']) ? true : false;
$niveau_filter = isset($_GET['niveau_filter']) ? $_GET['niveau_filter'] : 'جميع المستويات';

// Build the query
$query = "SELECT id, id_etudiant, nom, prenom, date_naissance, niveau, telephone, email, nom_parent FROM etudiants WHERE actif = 1";
$params = [];
$types = "";

if (!empty($search)) {
    $words = explode(' ', $search);
    $operator = $exact_match ? "=" : "LIKE";

    // Prepare value based on exact or partial match
    // define function only if not exists (safe for re-includes)
    if (!function_exists('pattern')) {
        function pattern($value, $exact)
        {
            return $exact ? $value : "%$value%";
        }
    }

    $conditions = [];

    // 1️⃣ Direct ID search
    $conditions[] = "id_etudiant $operator ?";
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

$query .= " ORDER BY nom, prenom";

// Prepare and execute
$stmt = $conn->prepare($query);
if ($stmt === false) {
    // handle prepare error
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
if (!empty($params)) {
    // bind types and params dynamically
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$total_students = $result->num_rows;

// If AJAX request, return only the fragment (results-info + table) and exit
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    // results-info
?>
    <div class="results-info">
        <strong>عدد الطلاب:</strong> <?= $total_students ?> طالب
        <?php if (!empty($search)): ?>
            | <strong>نتائج البحث عن:</strong> "<?= htmlspecialchars($search) ?>"
        <?php endif; ?>
        <?php if ($niveau_filter != "جميع المستويات"): ?>
            | <strong>المستوى:</strong> <?= htmlspecialchars($niveau_filter) ?>
        <?php endif; ?>
    </div>

    <!-- Students Table -->
    <table>
        <tr>
            <th>رقم التسجيل</th>
            <th>اللقب</th>
            <th>الاسم</th>
            <th>تاريخ الميلاد</th>
            <th>المستوى</th>
            <th>الهاتف</th>
            <th>الإجراءات</th>
        </tr>

        <?php if ($total_students > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_etudiant']) ?></td>
                    <td><?= htmlspecialchars($row['prenom']) ?></td>
                    <td><?= htmlspecialchars($row['nom']) ?></td>
                    <td><?= htmlspecialchars($row['date_naissance']) ?></td>
                    <td><?= htmlspecialchars($row['niveau']) ?></td>
                    <td><?= htmlspecialchars($row['telephone']) ?></td>
                    <td>
                        <a href="EditStudent.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">✏️ تعديل</a>
                        <a href="DeleteStudent.php?id=<?= $row['id'] ?>" class="action-btn delete-btn"
                            onclick="return confirm('هل أنت متأكد من حذف هذا الطالب من النظام ؟')">🗑️ حذف</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">
                    <?php if (!empty($search) || $niveau_filter != "جميع المستويات"): ?>
                        ❌ لم يتم العثور على طلاب مطابقين لمعايير البحث.
                    <?php else: ?>
                        📝 لا توجد طلاب مسجلين في النظام.
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>
<?php
    exit; // return fragment only for AJAX
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الطلاب</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ---------- your CSS (same as original) ---------- */
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
            background: #FFE1AF;
            direction: rtl;
            text-align: right;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 230px;
            background: linear-gradient(180deg, #B77466, #E2B59A);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding-top: 20px;
            box-shadow: -2px 0 5px rgba(0, 0, 0, .2);
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            z-index: 1000;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 0 10px;
        }

        .menu a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            transition: background .2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #957C62;
        }

        .content {
            flex: 1;
            margin-right: 240px;
            margin-top: 10px;
            padding: 15px;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .tab-btn {
            background: #B77466;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: .2s;
            font-size: 15px;
        }

        .tab-btn:hover,
        .tab-btn.active {
            background: #E2B59A;
        }

        .tab-content {
            display: none;
            opacity: 0;
            transform: translateX(50px);
            transition: all .4s ease;
        }

        .tab-content.active {
            display: block;
            opacity: 1;
            transform: translateX(0);
        }

        .search-nav {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px #ccc;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }

        .form-group {
            flex: 1;
            min-width: 200px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 5px;
        }

        .checkbox-group input {
            width: auto;
            margin: 0;
        }

        .search-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: .2s;
        }

        .btn-primary {
            background: #28a745;
            color: white;
        }

        .btn-primary:hover {
            background: #218838;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .results-info {
            margin-top: 15px;
            padding: 10px;
            background: #e9ecef;
            border-radius: 6px;
            font-size: 14px;
        }

        form {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px #ccc;
        }

        input,
        select,
        textarea,
        button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            background: #B77466;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background: #E2B59A;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px #ccc;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #B77466;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 2px;
            font-size: 14px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            transition: .2s;
        }

        .edit-btn {
            background: #ffc107;
        }

        .edit-btn:hover {
            background: #e0a800;
        }

        .delete-btn {
            background: #dc3545;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        @media (max-width:768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-right: 200px;
            }

            .search-form {
                flex-direction: column;
            }

            .form-group {
                min-width: 100%;
            }
        }

        @media (max-width:576px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .content {
                margin-right: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>📚 إدارة الطلاب</h2>
        <nav class="menu">
            <a href="../Dashbord/Dashbord.php">📊 الرئيسية</a>
            <a href="../Students/AddStudent.php" class="active">👨‍🎓 الطلاب</a>
            <a href="../Groups/Groups.php">👥 الأفواج</a>
            <a href="../AssingStudentsGroups/index.php">🔗 ربط الطلاب بالأفواج</a>
            <a href="../Presence/index.php">🕒 الحضور</a>
            <a href="../Reports/Report.php">📈 التقارير</a>
            <a href="../Login/logout.php" style="margin-top:auto;background:#d32f2f;">🚪 تسجيل الخروج</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="content">
        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="showTab(event, 'listTab')">📋 قائمة الطلاب</button>
            <button class="tab-btn" onclick="showTab(event, 'addTab')">➕ إضافة طالب</button>
        </div>

        <!-- Add Student Form -->
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
                <button type="submit">إضافة</button>
            </form>
        </div>

        <!-- Students List -->
        <div id="listTab" class="tab-content active">
            <!-- Search and Filter Navigation -->
            <div class="search-nav">
                <form method="GET" class="search-form" onsubmit="return false;">
                    <div class="form-group">
                        <label for="search">بحث عن الطلاب:</label>
                        <input type="text" id="search" name="search" class="search-input"
                            placeholder="ابحث برقم التسجيل، الاسم، اللقب، تاريخ الميلاد..."
                            value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                        <div class="checkbox-group">
                            <input type="checkbox" id="exact_match" name="exact_match" value="1"
                                <?= isset($_GET['exact_match']) ? 'checked' : '' ?>>
                            <label for="exact_match">بحث مطابق تماماً</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="niveau_filter">تصفية حسب المستوى:</label>
                        <select id="niveau_filter" name="niveau_filter" class="search-input">
                            <option value="جميع المستويات">جميع المستويات</option>
                            <option value="الابتدائي" <?= (isset($_GET['niveau_filter']) && $_GET['niveau_filter'] == 'الابتدائي') ? 'selected' : '' ?>>الابتدائي</option>
                            <option value="متوسط" <?= (isset($_GET['niveau_filter']) && $_GET['niveau_filter'] == 'متوسط') ? 'selected' : '' ?>>متوسط</option>
                            <option value="الثانوي" <?= (isset($_GET['niveau_filter']) && $_GET['niveau_filter'] == 'الثانوي') ? 'selected' : '' ?>>الثانوي</option>
                            <option value="بكالوريا" <?= (isset($_GET['niveau_filter']) && $_GET['niveau_filter'] == 'بكالوريا') ? 'selected' : '' ?>>بكالوريا</option>
                            <option value="جامعي" <?= (isset($_GET['niveau_filter']) && $_GET['niveau_filter'] == 'جامعي') ? 'selected' : '' ?>>جامعي</option>
                        </select>
                    </div>
                </form>

                <!-- Results info and Table (initial render comes from PHP variables prepared earlier) -->
                <div class="results-info">
                    <strong>عدد الطلاب:</strong> <?= $total_students ?> طالب
                    <?php if (!empty($search)): ?>
                        | <strong>نتائج البحث عن:</strong> "<?= htmlspecialchars($search) ?>"
                    <?php endif; ?>
                    <?php if ($niveau_filter != "جميع المستويات"): ?>
                        | <strong>المستوى:</strong> <?= htmlspecialchars($niveau_filter) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Students Table -->
            <table>
                <tr>
                    <th>رقم التسجيل</th>
                    <th>اللقب</th>
                    <th>الاسم</th>
                    <th>تاريخ الميلاد</th>
                    <th>المستوى</th>
                    <th>الرصيد </th>
                    <th>الهاتف</th>
                    <th>الإجراءات</th>
                </tr>

                <?php if ($total_students > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_etudiant']) ?></td>
                            <td><?= htmlspecialchars($row['prenom']) ?></td>
                            <td><?= htmlspecialchars($row['nom']) ?></td>
                            <td><?= htmlspecialchars($row['date_naissance']) ?></td>
                            <td><?= htmlspecialchars($row['niveau']) ?></td>
                            <td class="balance-cell" data-id="<?= $row['id'] ?>">...</td>
                            <td><?= htmlspecialchars($row['telephone']) ?></td>
                            <td>
                                <a href="EditStudent.php?id=<?= $row['id'] ?>" class="action-btn edit-btn">✏️ تعديل</a>
                                <a href="DeleteStudent.php?id=<?= $row['id'] ?>" class="action-btn delete-btn"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا الطالب من النظام ؟')">🗑️ حذف</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">
                            <?php if (!empty($search) || $niveau_filter != "جميع المستويات"): ?>
                                ❌ لم يتم العثور على طلاب مطابقين لمعايير البحث.
                            <?php else: ?>
                                📝 لا توجد طلاب مسجلين في النظام.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const cells = document.querySelectorAll(".balance-cell");
            for (const cell of cells) {
                const id = cell.dataset.id;
                try {
                    const res = await fetch(`Student_api.php?action=balance&id=${id}`);
                    const data = await res.json();
                    if (data.balance !== undefined) {
                        const balance = Number(data.balance).toFixed(2);
                        cell.textContent = balance + " دج";
                        cell.style.color = balance < 0 ? "red" : "green";
                    } else {
                        cell.textContent = "0.00 دج";
                    }
                } catch (e) {
                    cell.textContent = "خطأ";
                }
            }
        });


        function showTab(event, tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            event.target.classList.add('active');
        }

        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search') || urlParams.has('niveau_filter')) {
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
                document.getElementById('listTab').classList.add('active');
                document.querySelector('.tab-btn:nth-child(2)').classList.add('active');
            }

            initAutoSearch();
        });

        // Auto-search with AJAX (keeps your exact backend logic)
        function initAutoSearch() {
            const searchInput = document.getElementById("search");
            const niveauFilter = document.getElementById("niveau_filter");
            const exactMatch = document.getElementById("exact_match");
            const form = document.querySelector(".search-form");
            const listTab = document.getElementById("listTab");

            let timer;

            async function performSearch() {
                const params = new URLSearchParams(new FormData(form));
                params.set('ajax', '1');

                try {
                    const res = await fetch('?' + params.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const html = await res.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Replace results-info and table from returned fragment
                    const newInfo = doc.querySelector('.results-info');
                    const newTable = doc.querySelector('table');

                    const oldInfo = listTab.querySelector('.results-info');
                    const oldTable = listTab.querySelector('table');

                    if (newInfo && oldInfo) oldInfo.replaceWith(newInfo);
                    if (newTable && oldTable) oldTable.replaceWith(newTable);
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
    </script>
</body>

</html>