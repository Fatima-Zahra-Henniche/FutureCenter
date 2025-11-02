<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>لوحة التحكم - Gestion École</title>

  <link rel="stylesheet" href="Dashbord.css" />
</head>

<body>
  <aside class="sidebar">
    <?php include_once("../includes/SideBar.php"); ?>
  </aside>
  <div class="container">
    <header class="header">
      <h1>لوحة التحكم</h1>
    </header>

    <!-- Stat cards -->
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

      <!-- uncomment if you implement payments endpoint -->
      <!--
      <div class="stat-card" id="payments-card">
        <div class="stat-value" id="payments-sum">0</div>
        <div class="stat-title">المدفوعات</div>
        <div class="stat-desc">دينار اليوم</div>
      </div>
      -->
    </section>

    <!-- bottom section: recent students + active groups -->
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
      <small>تحديث آخر: <span id="last-updated">—</span></small>
    </footer>
  </div>

  <script src="Dachbord.js"></script>
</body>

</html>