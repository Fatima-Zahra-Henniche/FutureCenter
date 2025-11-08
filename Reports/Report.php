<?php
// Reports.php
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير والإحصائيات - Future Center</title>
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
        .container {
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

        .page-header h1 {
            color: var(--primary);
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .page-header p {
            color: var(--text-light);
            font-size: 1.1rem;
            margin: 0;
        }

        /* Search and Filter Sections */
        .search-box,
        .filter-box {
            background: var(--white);
            padding: 25px 30px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }

        .search-box:hover,
        .filter-box:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .section-title {
            color: var(--primary);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Search Box */
        .search-container {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 300px;
            padding: 14px 18px;
            border: 1px solid #ddd;
            border-radius: 12px;
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
            margin-right: 15px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .checkbox-group label {
            font-weight: 500;
            color: var(--text-dark);
        }

        /* Filter Box */
        .filter-container {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-group label {
            font-weight: 600;
            color: var(--text-dark);
            white-space: nowrap;
        }

        .filter-input {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            text-align: right;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
        }

        .btn-filter {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            font-family: 'Cairo', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-filter:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(149, 124, 98, 0.3);
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: var(--white);
            border-radius: 16px 16px 0 0;
            box-shadow: var(--shadow);
            overflow: hidden;
            margin-bottom: 0;
        }

        .tab {
            flex: 1;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: var(--transition);
            font-weight: 600;
            font-size: 1.1rem;
            border-bottom: 3px solid transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .tab:hover {
            background: var(--primary-light);
            color: var(--white);
        }

        .tab.active {
            background: var(--primary);
            color: var(--white);
            border-bottom-color: var(--secondary);
        }

        /* Tab Content */
        .tab-content {
            display: none;
            background: var(--white);
            padding: 30px;
            border-radius: 0 0 16px 16px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
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

        .tab-content h3 {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Tables */
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
            padding: 18px 16px;
            font-weight: 600;
            font-size: 1rem;
        }

        td {
            padding: 16px;
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

        /* Status Badges */
        .status-present {
            background: var(--success);
            color: var(--white);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-absent {
            background: var(--danger);
            color: var(--white);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .balance-positive {
            color: var(--success);
            font-weight: 600;
        }

        .balance-negative {
            color: var(--danger);
            font-weight: 600;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--primary-light);
            margin-bottom: 20px;
        }

        .empty-state h4 {
            margin-bottom: 10px;
            color: var(--text-dark);
            font-size: 1.3rem;
        }

        /* Responsive */
        @media (max-width: 1200px) {

            .search-container,
            .filter-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                min-width: auto;
            }

            .checkbox-group {
                margin-right: 0;
                justify-content: flex-end;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }

            .container {
                margin-right: 220px;
                padding: 20px;
            }

            .tabs {
                flex-direction: column;
            }

            .tab {
                border-bottom: none;
                border-left: 3px solid transparent;
            }

            .tab.active {
                border-left-color: var(--secondary);
                border-bottom-color: transparent;
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

            .container {
                margin-right: 70px;
                padding: 15px;
            }

            .page-header h1 {
                font-size: 1.6rem;
            }

            .search-box,
            .filter-box,
            .tab-content {
                padding: 20px;
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

            .container {
                margin-right: 0;
                padding: 15px;
            }

            .filter-group {
                flex-direction: column;
                align-items: flex-start;
                width: 100%;
            }

            .filter-input {
                width: 100%;
            }

            .btn-filter {
                width: 100%;
                justify-content: center;
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
            <a href="../Students/AddStudent.php"><i class="fas fa-user-graduate"></i> <span>الطلاب</span></a>
            <a href="../Groups/Groups.php"><i class="fas fa-users"></i> <span>الأفواج</span></a>
            <a href="../AssingStudentsGroups/index.php"><i class="fas fa-link"></i> <span>ربط الطلاب بالأفواج</span></a>
            <a href="../Presence/index.php"><i class="fas fa-clock"></i> <span>الحضور</span></a>
            <a href="../Reports/Report.php" class="active"><i class="fas fa-chart-bar"></i> <span>التقارير</span></a>
            <a href="../Login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span></a>
        </nav>
    </aside>

    <div class="container">
        <div class="page-header">
            <h1><i class="fas fa-chart-bar"></i> التقارير والإحصائيات</h1>
            <p>عرض وتحليل بيانات الطلاب والحضور والمدفوعات</p>
        </div>

        <!-- 🔍 بحث -->
        <div class="search-box">
            <h3 class="section-title"><i class="fas fa-search"></i> بحث متقدم</h3>
            <div class="search-container">
                <input type="text" id="search" class="search-input" placeholder="اكتب اسم الطالب أو اللقب أو رقم التسجيل...">
                <div class="checkbox-group">
                    <input type="checkbox" id="exactMatch">
                    <label for="exactMatch">بحث متطابق</label>
                </div>
            </div>
        </div>

        <!-- 📅 فلاتر التاريخ -->
        <div class="filter-box">
            <h3 class="section-title"><i class="fas fa-filter"></i> تصفية النتائج</h3>
            <div class="filter-container">
                <div class="filter-group">
                    <label for="dateFrom">من:</label>
                    <input type="date" id="dateFrom" class="filter-input">
                </div>
                <div class="filter-group">
                    <label for="dateTo">إلى:</label>
                    <input type="date" id="dateTo" class="filter-input">
                </div>
                <button id="filterBtn" class="btn-filter">
                    <i class="fas fa-sliders-h"></i>
                    تطبيق الفلتر
                </button>
            </div>
        </div>

        <!-- 🗂️ التبويبات -->
        <div class="tabs">
            <div class="tab active" data-tab="attendance">
                <i class="fas fa-clock"></i>
                تقرير الحضور
            </div>
            <div class="tab" data-tab="payments">
                <i class="fas fa-money-bill-wave"></i>
                تقرير المدفوعات
            </div>
            <div class="tab" data-tab="students">
                <i class="fas fa-user-graduate"></i>
                تقرير الطلاب
            </div>
        </div>

        <!-- 📋 محتوى التبويبات -->
        <div class="tab-content active" id="attendance">
            <h3><i class="fas fa-clock"></i> تقرير الحضور والغياب</h3>
            <div class="table-container">
                <table id="attendanceTable">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الفوج</th>
                            <th>رقم التسجيل</th>
                            <th>الطالب</th>
                            <th>من</th>
                            <th>إلى</th>
                            <th>الحالة</th>
                            <th>المبلغ المخصوم</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- البيانات سيتم تحميلها ديناميكياً -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-content" id="payments">
            <h3><i class="fas fa-money-bill-wave"></i> تقرير المدفوعات</h3>
            <div class="table-container">
                <table id="paymentsTable">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>رقم التسجيل</th>
                            <th>الطالب</th>
                            <th>المبلغ</th>
                            <th>نوع الدفع</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- البيانات سيتم تحميلها ديناميكياً -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-content" id="students">
            <h3><i class="fas fa-user-graduate"></i> تقرير الطلاب والأفواج</h3>
            <div class="table-container">
                <table id="studentsTable">
                    <thead>
                        <tr>
                            <th>رقم التسجيل</th>
                            <th>الاسم</th>
                            <th>اللقب</th>
                            <th>المستوى</th>
                            <th>تاريخ التسجيل</th>
                            <th>عدد الأفواج</th>
                            <th>الرصيد</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- البيانات سيتم تحميلها ديناميكياً -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="Report.js"></script>
    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to current tab and content
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });

            // Initialize with sample data (replace with actual data loading)
            initializeSampleData();
        });

        function initializeSampleData() {
            // This is just for demonstration - replace with actual data loading
            console.log('Initializing reports page...');
        }
    </script>
</body>

</html>