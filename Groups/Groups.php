<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <title>إدارة الأفواج</title>
  <link rel="stylesheet" href="Groups.css">
</head>

<body>
  <aside class="sidebar">
    <?php include_once("../includes/SideBar.php"); ?>
  </aside>
  <div class="container">
    <h1>الأفواج</h1>

    <div class="tabs">
      <button class="tab-button active" data-tab="add">➕ إضافة فوج</button>
      <button class="tab-button" data-tab="list">📋 قائمة الأفواج</button>
    </div>

    <!-- ✅ تبويب إضافة فوج -->
    <div class="tab-content active" id="add">
      <div class="group-form">
        <label>اسم الفوج:</label>
        <input type="text" id="nom">

        <label>سعر الحصة (DA):</label>
        <input type="number" id="prix">

        <label>السعة القصوى:</label>
        <input type="number" id="capacite" value="20">

        <button id="add-btn">إنشاء الفوج</button>
      </div>
    </div>

    <!-- ✅ تبويب قائمة الأفواج -->
    <div class="tab-content" id="list">
      <div class="search-bar">
        <label>بحث:</label>
        <input type="text" id="search" placeholder="ابحث عن فوج...">
      </div>

      <table id="groups-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>اسم الفوج</th>
            <th>سعر الحصة</th>
            <th>السعة</th>
            <th>عدد الطلاب</th>
            <th>تعديل</th>
            <th>حذف</th>
          </tr>
        </thead>
        <tbody>
          <!-- بيانات الديناميكية من JS -->
        </tbody>
      </table>
    </div>

  </div>

  <script src="Groups.js"></script>
</body>

</html>