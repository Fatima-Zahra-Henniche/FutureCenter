<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>لوحة التحكم - Gestion École</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Dashbord.css" />
  <style>
    /* === GENERAL STYLE === */
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
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      padding: 0;
      background: var(--background);
      direction: rtl;
      text-align: right;
      display: flex;
      min-height: 140vh;
      color: var(--text-dark);
    }

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

    .container {
      flex: 1;
      margin-right: 260px;
      padding: 25px;
      box-sizing: border-box;
      transition: var(--transition);
    }

    /* === DASHBOARD STYLE === */
    .header-image {
      width: 100%;
      height: 250px;
      border-radius: 16px;
      object-fit: cover;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
      transition: var(--transition);
    }

    .stats-row {
      display: flex;
      flex-wrap: wrap;
      gap: 25px;
      justify-content: space-between;
      margin-bottom: 30px;
    }

    .stat-card {
      flex: 1;
      min-width: 220px;
      background: var(--white);
      padding: 25px 20px;
      border-radius: 16px;
      text-align: center;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--primary-light));
    }

    .stat-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
      font-size: 2.5rem;
      color: var(--primary);
      margin-bottom: 15px;
      opacity: 0.8;
    }

    .stat-value {
      font-size: 2.5rem;
      color: var(--primary);
      font-weight: 700;
      margin: 10px 0;
    }

    .stat-title {
      font-size: 1.2rem;
      margin: 8px 0;
      color: var(--text-dark);
      font-weight: 600;
    }

    .stat-desc {
      color: var(--text-light);
      font-size: 0.9rem;
    }

    .bottom-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 25px;
      margin-bottom: 30px;
    }

    .panel {
      background: var(--white);
      border-radius: 16px;
      box-shadow: var(--shadow);
      padding: 25px;
      transition: var(--transition);
    }

    .panel:hover {
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .panel-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .panel h2 {
      margin-top: 0;
      color: var(--primary);
      border-bottom: 2px solid var(--primary-light);
      padding-bottom: 10px;
      margin-bottom: 20px;
      font-weight: 700;
      font-size: 1.4rem;
    }

    .view-all {
      color: var(--primary);
      text-decoration: none;
      font-size: 0.9rem;
      transition: var(--transition);
    }

    .view-all:hover {
      color: var(--secondary);
    }

    .table-wrap {
      overflow-x: auto;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
      min-width: 400px;
    }

    .data-table th,
    .data-table td {
      padding: 14px 16px;
      text-align: center;
      border-bottom: 1px solid #f0f0f0;
    }

    .data-table th {
      background: var(--primary);
      color: var(--white);
      font-weight: 600;
    }

    .data-table tr:last-child td {
      border-bottom: none;
    }

    .data-table tr:hover {
      background: #f9f9f9;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      color: var(--text-light);
      padding: 15px;
      background: var(--white);
      border-radius: 12px;
      box-shadow: var(--shadow);
    }

    /* === RESPONSIVE STYLES === */
    @media (max-width: 1200px) {
      .bottom-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 992px) {
      .sidebar {
        width: 220px;
      }

      .container {
        margin-right: 220px;
      }

      .stats-row {
        gap: 15px;
      }

      .stat-card {
        min-width: calc(50% - 15px);
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

      .header-image {
        height: 180px;
      }

      .stats-row {
        gap: 15px;
      }

      .stat-card {
        min-width: 100%;
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

      .header-image {
        height: 150px;
      }
    }

    /* === ANIMATIONS === */
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

    .stat-card,
    .panel {
      animation: fadeIn 0.5s ease forwards;
    }

    .stat-card:nth-child(1) {
      animation-delay: 0.1s;
    }

    .stat-card:nth-child(2) {
      animation-delay: 0.2s;
    }

    .panel:nth-child(1) {
      animation-delay: 0.3s;
    }

    .panel:nth-child(2) {
      animation-delay: 0.4s;
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
      <a href="../Dashbord/Dashbord.php" class="active"><i class="fas fa-chart-line"></i> <span>الرئيسية</span></a>
      <a href="../Students/AddStudent.php"><i class="fas fa-user-graduate"></i> <span>الطلاب</span></a>
      <a href="../Groups/Groups.php"><i class="fas fa-users"></i> <span>الأفواج</span></a>
      <a href="../AssingStudentsGroups/index.php"><i class="fas fa-link"></i> <span>ربط الطلاب بالأفواج</span></a>
      <a href="../Presence/index.php"><i class="fas fa-clock"></i> <span>الحضور</span></a>
      <a href="../Reports/Report.php"><i class="fas fa-chart-bar"></i> <span>التقارير</span></a>
      <a href="../Login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span></a>
    </nav>
  </aside>

  <div class="container">
    <img src="../img/Future.jpg" alt="Future Center Image Poster" class="header-image" />

    <section class="stats-row">
      <div class="stat-card" id="students-card">
        <div class="stat-icon">
          <i class="fas fa-user-graduate"></i>
        </div>
        <div class="stat-value" id="students-count">0</div>
        <div class="stat-title">الطلاب</div>
        <div class="stat-desc">طلاب مسجلين في النظام</div>
      </div>

      <div class="stat-card" id="groups-card">
        <div class="stat-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stat-value" id="groups-count">0</div>
        <div class="stat-title">الأفواج</div>
        <div class="stat-desc">فوج نشط في المركز</div>
      </div>
    </section>

    <section class="bottom-row">
      <div class="panel">
        <div class="panel-header">
          <h2>آخر الطلاب المسجلين</h2>
          <a href="../Students/AddStudent.php" class="view-all">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="table-wrap">
          <table class="data-table" id="recent-students-table">
            <thead>
              <tr>
                <th>رقم التسجيل</th>
                <th>اللقب</th>
                <th>الاسم</th>
                <th>المستوى</th>
                <th>تاريخ التسجيل</th>
              </tr>
            </thead>
            <tbody id="recent-students-body">
              <!-- rows injected by JS -->
            </tbody>
          </table>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <h2>الأفواج النشطة</h2>
          <a href="../Groups/Groups.php" class="view-all">عرض الكل <i class="fas fa-arrow-left"></i></a>
        </div>
        <div class="table-wrap">
          <table class="data-table" id="active-groups-table">
            <thead>
              <tr>
                <th>اسم الفوج</th>
                <th>عدد الطلاب</th>
                <th>سعر الحصة</th>
              </tr>
            </thead>
            <tbody id="active-groups-body">
              <!-- rows injected by JS -->
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <footer class="footer">
      <small> آخر تحديث : <span id="last-updated">—</span></small>
    </footer>
  </div>

  <script src="Dachbord.js"></script>
</body>

</html>