<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>لوحة التحكم - Gestion École</title>
  <link rel="stylesheet" href="Dashbord.css" />
  <style>
    /* === GENERAL STYLE (same as Students page) === */
    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      padding: 0;
      background: #FFE1AF;
      direction: rtl;
      text-align: right;
      display: flex;
      min-height: 140vh;
    }

    .sidebar {
      width: 225px;
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

    .container {
      flex: 1;
      margin-right: 240px;
      padding: 5px;
      box-sizing: border-box;
    }

    /* === DASHBOARD STYLE === */
    .header-image {
      width: 100%;
      height: 250px;
      border-radius: 12px;
      object-fit: fill;
      box-shadow: 0 2px 5px rgba(0, 0, 0, .2);
      margin-bottom: 15px;
    }

    /* .header h1 {
      text-align: center;
      color: #B77466;
      margin-bottom: 30px;
    } */

    .stats-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: space-around;
      margin-bottom: 10px;
    }

    .stat-card {
      flex: 1;
      min-width: 220px;
      background: white;
      padding: 5px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 2px 4px #ccc;
      transition: transform .2s ease, box-shadow .2s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, .2);
    }

    .stat-value {
      font-size: 2em;
      color: #B77466;
      font-weight: bold;
    }

    .stat-title {
      font-size: 1.2em;
      margin: 8px 0;
      color: #333;
    }

    .stat-desc {
      color: #666;
      font-size: .9em;
    }

    .bottom-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }

    .panel {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 4px #ccc;
      padding: 15px;
    }

    .panel h2 {
      margin-top: 0;
      color: #B77466;
      border-bottom: 2px solid #E2B59A;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
    }

    .data-table th,
    .data-table td {
      padding: 10px 12px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }

    .data-table th {
      background: #B77466;
      color: white;
    }

    .data-table tr:hover {
      background: #f5f5f5;
    }

    .footer {
      text-align: center;
      margin-top: 30px;
      color: #555;
    }

    @media (max-width: 768px) {
      .bottom-row {
        grid-template-columns: 1fr;
      }

      .sidebar {
        width: 200px;
      }

      .container {
        margin-right: 200px;
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
      }

      .container {
        margin-right: 0;
      }

      .bottom-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <aside class="sidebar">
    <h2>Future Center</h2>
    <nav class="menu">
      <a href="../Dashbord/Dashbord.php" class="active">📊 الرئيسية</a>
      <a href="../Students/AddStudent.php">👨‍🎓 الطلاب</a>
      <a href="../Groups/Groups.php">👥 الأفواج</a>
      <a href="../AssingStudentsGroups/index.php">🔗 ربط الطلاب بالأفواج</a>
      <a href="../Presence/index.php">🕒 الحضور</a>
      <a href="../Reports/Report.php">📈 التقارير</a>
      <a href="../Login/logout.php" style="margin-top:auto;background:#d32f2f;">🚪 تسجيل الخروج</a>
    </nav>
  </aside>

  <div class="container">
    <img src="../img/Future.jpg" alt="Future Center Image Poster" class="header-image" />

    <section class="stats-row">
      <div class="stat-card" id="students-card">
        <div class="stat-value" id="students-count">0</div>
        <div class="stat-title">الطلاب</div>
        <div class="stat-desc">طلاب مسجلين</div>
      </div>

      <div class="stat-card" id="groups-card">
        <div class="stat-value" id="groups-count">0</div>
        <div class="stat-title">الأفواج</div>
        <div class="stat-desc">فوج نشط</div>
      </div>
    </section>

    <section class="bottom-row">
      <div class="panel">
        <h2>آخر الطلاب المسجلين</h2>
        <div class="table-wrap">
          <table class="data-table" id="recent-students-table">
            <thead>
              <tr>
                <th>رقم التسجيل</th>
                <th>الاسم</th>
                <th>اللقب</th>
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
        <h2>الأفواج النشطة</h2>
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