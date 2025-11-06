<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <title>إدارة الأفواج</title>
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
    .container {
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

    .tab-button {
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

    .tab-button.active {
      background: var(--primary);
      color: var(--white);
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(183, 116, 102, 0.3);
    }

    .tab-button:hover {
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

    /* Form */
    .group-form {
      max-width: 500px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 20px;
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

    .form-group input {
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 16px;
      text-align: right;
      transition: var(--transition);
      font-family: 'Cairo', sans-serif;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
    }

    .form-actions {
      display: flex;
      gap: 15px;
      margin-top: 10px;
    }

    .btn {
      padding: 12px 25px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      font-family: 'Cairo', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-primary {
      background: var(--primary);
      color: var(--white);
      flex: 1;
    }

    .btn-primary:hover {
      background: var(--secondary);
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(149, 124, 98, 0.3);
    }

    .btn-secondary {
      background: var(--white);
      color: var(--primary);
      border: 2px solid var(--primary);
    }

    .btn-secondary:hover {
      background: var(--primary-light);
      color: var(--white);
    }

    /* Search Bar */
    .search-bar {
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 10px;
    }

    .search-bar label {
      font-weight: 600;
      color: var(--text-dark);
    }

    .search-bar input {
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 10px;
      width: 300px;
      text-align: right;
      font-family: 'Cairo', sans-serif;
      transition: var(--transition);
    }

    .search-bar input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
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

    .action-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
    }

    .btn-edit,
    .btn-delete {
      border: none;
      padding: 8px 14px;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: var(--transition);
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
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

    /* Modal */
    .custom-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 9999;
      backdrop-filter: blur(2px);
    }

    .custom-modal-content {
      background: #fff;
      width: 450px;
      border-radius: 16px;
      padding: 30px;
      text-align: right;
      animation: popIn 0.3s ease;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid var(--primary-light);
    }

    .modal-header h2 {
      color: var(--primary);
      font-size: 1.5rem;
    }

    .modal-actions {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 25px;
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

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        width: 220px;
      }

      .container {
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

      .container {
        margin-right: 70px;
        padding: 15px;
      }

      .tabs {
        flex-direction: column;
        gap: 10px;
      }

      .tab-button {
        width: 100%;
        justify-content: center;
      }

      .search-bar {
        flex-direction: column;
        align-items: flex-end;
      }

      .search-bar input {
        width: 100%;
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

      .custom-modal-content {
        width: 95%;
        padding: 20px;
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
      <a href="../Groups/Groups.php" class="active"><i class="fas fa-users"></i> <span>الأفواج</span></a>
      <a href="../AssingStudentsGroups/index.php"><i class="fas fa-link"></i> <span>ربط الطلاب بالأفواج</span></a>
      <a href="../Presence/index.php"><i class="fas fa-clock"></i> <span>الحضور</span></a>
      <a href="../Reports/Report.php"><i class="fas fa-chart-bar"></i> <span>التقارير</span></a>
      <a href="../Login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span></a>
    </nav>
  </aside>

  <div class="container">
    <div class="tabs">
      <button class="tab-button active" data-tab="list">
        <i class="fas fa-list"></i> قائمة الأفواج
      </button>
      <button class="tab-button" data-tab="add">
        <i class="fas fa-plus"></i> إضافة فوج
      </button>
    </div>

    <!-- Add group -->
    <div class="tab-content" id="add">
      <div class="group-form">
        <div class="form-group">
          <label for="nom">اسم الفوج:</label>
          <input type="text" id="nom" placeholder="أدخل اسم الفوج">
        </div>

        <div class="form-group">
          <label for="prix">سعر الحصة (DA):</label>
          <input type="number" id="prix" placeholder="أدخل سعر الحصة">
        </div>

        <div class="form-group">
          <label for="capacite">السعة القصوى:</label>
          <input type="number" id="capacite" value="20" placeholder="أدخل السعة القصوى">
        </div>

        <div class="form-actions">
          <button id="add-btn" class="btn btn-primary">
            <i class="fas fa-save"></i> إنشاء الفوج
          </button>
          <button class="btn btn-secondary" id="reset-form">
            <i class="fas fa-redo"></i> إعادة تعيين
          </button>
        </div>
      </div>
    </div>

    <!-- List groups -->
    <div class="tab-content active" id="list">
      <div class="search-bar">
        <label for="search">بحث:</label>
        <input type="text" id="search" placeholder="ابحث عن فوج...">
      </div>

      <div class="table-container">
        <table id="groups-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>اسم الفوج</th>
              <th>سعر الحصة</th>
              <th>السعة</th>
              <th>عدد الطلاب</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Edit Modal -->
  <div id="editModal" class="custom-modal">
    <div class="custom-modal-content">
      <div class="modal-header">
        <h2><i class="fas fa-edit"></i> تعديل معلومات الفوج</h2>
      </div>

      <div class="form-group">
        <label for="editNom">اسم الفوج</label>
        <input type="text" id="editNom">
      </div>

      <div class="form-group">
        <label for="editPrix">سعر الحصة (دج)</label>
        <input type="number" id="editPrix">
      </div>

      <div class="form-group">
        <label for="editCapacite">السعة القصوى</label>
        <input type="number" id="editCapacite">
      </div>

      <div class="modal-actions">
        <button id="saveEdit" class="btn btn-primary">
          <i class="fas fa-save"></i> حفظ التغييرات
        </button>
        <button id="cancelEdit" class="btn btn-secondary">
          <i class="fas fa-times"></i> إلغاء
        </button>
      </div>
    </div>
  </div>

  <script src="Groups.js"></script>
</body>

</html>