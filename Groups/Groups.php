<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <title>إدارة الأفواج</title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #B77466;
      --secondary: #E2B59A;
      --bg: #FFF7EE;
      --text: #333;
      --white: #fff;
    }

    body {
      font-family: 'Cairo', sans-serif;
      margin: 0;
      display: flex;
      direction: rtl;
      background: var(--bg);
      color: var(--text);
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background: linear-gradient(180deg, var(--primary), var(--secondary));
      color: var(--white);
      display: flex;
      flex-direction: column;
      padding: 20px;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 22px;
      font-weight: 700;
    }

    .menu {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .menu a {
      text-decoration: none;
      color: var(--white);
      background: rgba(255, 255, 255, 0.1);
      padding: 10px 15px;
      border-radius: 8px;
      transition: 0.3s;
      font-weight: 500;
    }

    .menu a:hover,
    .menu a.active {
      background: var(--white);
      color: var(--primary);
      font-weight: 600;
    }

    .menu a:last-child {
      margin-top: auto;
      background: #c0392b;
      text-align: center;
    }

    .menu a:last-child:hover {
      background: #e74c3c;
    }

    /* Main */
    .container {
      flex: 1;
      padding: 30px;
      overflow-y: auto;
    }

    .tabs {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 25px;
    }

    .tab-button {
      background: var(--primary);
      color: var(--white);
      border: none;
      padding: 10px 25px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
      font-size: 15px;
    }

    .tab-button.active {
      background: var(--secondary);
      color: var(--text);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    .tab-button:hover {
      background: var(--secondary);
      color: var(--text);
    }

    .tab-content {
      display: none;
      background: var(--white);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.3s ease-in-out;
    }

    .search-bar {
      margin-bottom: 20px;
      text-align: right;
    }

    .search-bar label {
      margin-left: 10px;
      font-weight: 600;
    }

    .search-bar input {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 250px;
      text-align: right;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Form */
    .group-form {
      max-width: 400px;
      margin: auto;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .group-form input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      text-align: right;
    }

    .group-form button {
      background: var(--primary);
      color: var(--white);
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }

    .group-form button:hover {
      background: var(--secondary);
      color: var(--text);
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
      font-size: 15px;
      background: var(--white);
      border-radius: 10px;
      overflow: hidden;
    }

    th {
      background: var(--primary);
      color: var(--white);
      padding: 14px;
    }

    td {
      padding: 12px;
      border-bottom: 1px solid #eee;
    }

    tr:nth-child(even) {
      background: #fafafa;
    }

    .edit-btn,
    .delete-btn {
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      transition: 0.3s;
    }

    .edit-btn {
      background: #2980b9;
      color: #fff;
    }

    .edit-btn:hover {
      background: #3498db;
    }

    .delete-btn {
      background: #c0392b;
      color: #fff;
    }

    .delete-btn:hover {
      background: #e74c3c;
    }

    /* Modal */
    .custom-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.45);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .custom-modal-content {
      background: #fff;
      width: 420px;
      border-radius: 16px;
      padding: 25px 30px;
      text-align: right;
      animation: popIn 0.3s ease;
    }

    @keyframes popIn {
      from {
        transform: scale(0.9);
        opacity: 0;
      }

      to {
        transform: scale(1);
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <aside class="sidebar">
    <h2>👥 إدارة الأفواج</h2>
    <nav class="menu">
      <a href="../Dashbord/Dashbord.php">📊 الرئيسية</a>
      <a href="../Students/AddStudent.php">👨‍🎓 الطلاب</a>
      <a href="../Groups/Groups.php" class="active">👥 الأفواج</a>
      <a href="../AssingStudentsGroups/index.php">🔗 ربط الطلاب بالأفواج</a>
      <a href="../Presence/index.php">🕒 الحضور</a>
      <a href="../Reports/Report.php">📈 التقارير</a>
      <a href="../Login/logout.php">🚪 تسجيل الخروج</a>
    </nav>
  </aside>

  <div class="container">
    <div class="tabs">
      <button class="tab-button active" data-tab="list">📋 قائمة الأفواج</button>
      <button class="tab-button" data-tab="add">➕ إضافة فوج</button>
    </div>

    <!-- Add group -->
    <div class="tab-content" id="add">
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

    <!-- List groups -->
    <div class="tab-content active" id="list">
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
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="custom-modal">
    <div class="custom-modal-content">
      <h2>تعديل معلومات الفوج</h2>
      <label>اسم الفوج</label>
      <input type="text" id="editNom">
      <label>سعر الحصة (دج)</label>
      <input type="number" id="editPrix">
      <label>السعة القصوى</label>
      <input type="number" id="editCapacite">
      <div style="text-align:center;margin-top:15px;">
        <button id="saveEdit" class="edit-btn">💾 حفظ</button>
        <button id="cancelEdit" class="delete-btn">إلغاء</button>
      </div>
    </div>
  </div>

  <script src="Groups.js"></script>
</body>

</html>