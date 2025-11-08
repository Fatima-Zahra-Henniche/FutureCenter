<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الحضور - Future Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #B77466;
            --primary-light: #E2B59A;
            --secondary: #DEBA9D;
            --background: #FFF7EE;
            --white: #ffffff;
            --text-dark: #333333;
            --text-light: #666666;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
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
        .main-content {
            flex: 1;
            margin-right: 260px;
            padding: 30px;
            box-sizing: border-box;
            transition: var(--transition);
        }

        /* Header */
        .page-header {
            background: var(--white);
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
            text-align: center;
        }

        .page-header h2 {
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .page-header p {
            color: var(--text-light);
            font-size: 1.1rem;
            margin: 0;
        }

        /* Sections */
        .section {
            background: var(--white);
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .section:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            color: var(--primary);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Controls */
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .form-control {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            text-align: right;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
        }

        /* Students List */
        .students-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 15px;
            background: #fafafa;
        }

        .student-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: var(--white);
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: var(--transition);
        }

        .student-item:hover {
            border-color: var(--primary-light);
            transform: translateX(-5px);
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .student-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .student-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .student-name {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .student-id {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .attendance-controls {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .attendance-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-present {
            background: var(--success);
            color: var(--white);
        }

        .btn-present:hover {
            background: #219653;
            transform: translateY(-2px);
        }

        .btn-absent {
            background: var(--danger);
            color: var(--white);
        }

        .btn-absent:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .btn-present.active {
            background: var(--success);
            box-shadow: 0 0 0 2px var(--white), 0 0 0 4px var(--success);
        }

        .btn-absent.active {
            background: var(--danger);
            box-shadow: 0 0 0 2px var(--white), 0 0 0 4px var(--danger);
        }

        /* Save Button */
        .save-section {
            text-align: center;
            margin: 30px 0;
        }

        .btn-save {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 16px 40px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow);
        }

        .btn-save:hover {
            background: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(149, 124, 98, 0.3);
        }

        .btn-save:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Report Table */
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
            min-width: 800px;
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

        .status-present {
            color: var(--success);
            font-weight: 600;
        }

        .status-absent {
            color: var(--danger);
            font-weight: 600;
        }

        .balance-positive {
            color: var(--success);
            font-weight: 600;
        }

        .balance-negative {
            color: var(--danger);
            font-weight: 600;
        }

        /* Summary Stats */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-card {
            background: var(--white);
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow);
            border-top: 4px solid var(--primary);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin: 10px 0;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }

            .main-content {
                margin-right: 220px;
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
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

            .main-content {
                margin-right: 70px;
                padding: 15px;
            }

            .student-item {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .student-info {
                flex-direction: column;
                text-align: center;
            }

            .attendance-controls {
                width: 100%;
                justify-content: center;
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

            .main-content {
                margin-right: 0;
                padding: 15px;
            }

            .section {
                padding: 20px;
            }

            .stats-summary {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .student-item {
            animation: fadeIn 0.3s ease;
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
            <a href="../Students/AddStudent.php"><i class="fas fa-user-graduate"></i> <span>الطلاب</span></a>
            <a href="../Groups/Groups.php"><i class="fas fa-users"></i> <span>الأفواج</span></a>
            <a href="../AssingStudentsGroups/index.php"><i class="fas fa-link"></i> <span>ربط الطلاب بالأفواج</span></a>
            <a href="../Presence/index.php" class="active"><i class="fas fa-clock"></i> <span>الحضور</span></a>
            <a href="../Reports/Report.php"><i class="fas fa-chart-bar"></i> <span>التقارير</span></a>
            <a href="../Login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span></a>
        </nav>
    </aside>

    <div class="main-content">
        <div class="page-header">
            <h2><i class="fas fa-clock"></i> تسجيل الحضور</h2>
            <p>إدارة حضور الطلاب وتسجيل الحصص الدراسية</p>
        </div>

        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-calendar-alt"></i>
                إعدادات الجلسة
            </h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">التاريخ:</label>
                    <input type="date" id="date" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label for="time_start">وقت البدء:</label>
                    <input type="time" id="time_start" class="form-control" value="08:00">
                </div>
                <div class="form-group">
                    <label for="time_end">وقت الانتهاء:</label>
                    <input type="time" id="time_end" class="form-control" value="10:00">
                </div>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-users"></i>
                اختيار الفوج
            </h3>
            <div class="form-group">
                <label for="group">الفوج:</label>
                <select id="group" class="form-control">
                    <!-- Groups will be loaded dynamically -->
                </select>
            </div>
        </div>

        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-user-graduate"></i>
                قائمة الطلاب
            </h3>
            <div class="students-container" id="students">
                <!-- Students will be loaded here dynamically -->
            </div>
        </div>

        <div class="save-section">
            <button id="saveBtn" class="btn-save">
                <i class="fas fa-save"></i>
                حفظ الحضور وخصم السعر
            </button>
        </div>

        <div class="section">
            <h3 class="section-title">
                <i class="fas fa-chart-bar"></i>
                تقرير الحضور
            </h3>
            <div class="stats-summary">
                <div class="stat-card">
                    <div class="stat-value" id="total-students">0</div>
                    <div class="stat-label">إجمالي الطلاب</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="present-count">0</div>
                    <div class="stat-label">الحاضرين</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="absent-count">0</div>
                    <div class="stat-label">الغائبين</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="attendance-rate">0%</div>
                    <div class="stat-label">نسبة الحضور</div>
                </div>
            </div>

            <div class="table-container">
                <table id="report">
                    <thead>
                        <tr>
                            <th>الاسم</th>
                            <th>اللقب</th>
                            <th>الحالة</th>
                            <th>الرصيد قبل</th>
                            <th>الرصيد بعد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Report data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="Script.js"></script>

</body>

</html>