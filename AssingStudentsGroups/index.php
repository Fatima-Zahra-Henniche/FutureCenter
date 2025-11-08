<?php include "load_Data.php"; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ربط الطلاب بالأفواج - Future Center</title>
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

    /* Search Section */
    .search-section {
      background: var(--white);
      padding: 25px;
      border-radius: 16px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
    }

    .search-container {
      display: flex;
      align-items: center;
      gap: 15px;
      max-width: 600px;
      margin: 0 auto;
    }

    .search-icon {
      color: var(--primary);
      font-size: 1.2rem;
    }

    .search-input {
      flex: 1;
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

    /* Main Form */
    .main-form {
      background: var(--white);
      padding: 30px;
      border-radius: 16px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
    }

    .form-section {
      margin-bottom: 30px;
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

    /* Students Grid */
    .students-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 15px;
      max-height: 400px;
      overflow-y: auto;
      padding: 10px;
      border: 1px solid #eee;
      border-radius: 12px;
      background: #fafafa;
    }

    .student-card {
      background: var(--white);
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      padding: 15px;
      transition: var(--transition);
      cursor: pointer;
    }

    .student-card:hover {
      border-color: var(--primary-light);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .student-card.selected {
      border-color: var(--primary);
      background: linear-gradient(135deg, var(--primary-light), var(--primary));
      color: var(--white);
    }

    .student-info {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .student-name {
      font-weight: 600;
      font-size: 1.1rem;
    }

    .student-details {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      color: inherit;
      opacity: 0.8;
    }

    .student-id {
      font-family: monospace;
      background: rgba(0, 0, 0, 0.1);
      padding: 2px 6px;
      border-radius: 4px;
    }

    .student-card.selected .student-id {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Group Selection */
    .group-selection {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 25px;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 12px;
    }

    .group-selection label {
      font-weight: 600;
      color: var(--text-dark);
      font-size: 1.1rem;
      white-space: nowrap;
    }

    .group-select {
      flex: 1;
      max-width: 400px;
      padding: 12px 16px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 16px;
      text-align: right;
      transition: var(--transition);
      font-family: 'Cairo', sans-serif;
      background: var(--white);
    }

    .group-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(183, 116, 102, 0.2);
    }

    /* Buttons */
    .action-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 30px;
    }

    .btn {
      padding: 14px 30px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      font-family: 'Cairo', sans-serif;
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 180px;
      justify-content: center;
    }

    .btn-primary {
      background: var(--primary);
      color: var(--white);
    }

    .btn-primary:hover {
      background: var(--secondary);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(149, 124, 98, 0.3);
    }

    .btn-secondary {
      background: #e74c3c;
      color: var(--white);
    }

    .btn-secondary:hover {
      background: #c0392b;
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(192, 57, 43, 0.3);
    }

    .btn:disabled {
      background: #ccc;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Selection Counter */
    .selection-counter {
      text-align: center;
      margin: 15px 0;
      color: var(--primary);
      font-weight: 600;
      font-size: 1.1rem;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--text-light);
    }

    .empty-state i {
      font-size: 3rem;
      color: var(--primary-light);
      margin-bottom: 15px;
    }

    .empty-state h3 {
      margin-bottom: 10px;
      color: var(--text-dark);
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

      .students-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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

      .action-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn {
        width: 100%;
        max-width: 300px;
      }

      .group-selection {
        flex-direction: column;
        align-items: stretch;
      }

      .group-select {
        max-width: none;
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

      .students-grid {
        grid-template-columns: 1fr;
      }

      .search-container {
        flex-direction: column;
        gap: 10px;
      }

      .search-input {
        width: 100%;
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

    .student-card {
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
      <a href="../AssingStudentsGroups/index.php" class="active"><i class="fas fa-link"></i> <span>ربط الطلاب بالأفواج</span></a>
      <a href="../Presence/index.php"><i class="fas fa-clock"></i> <span>الحضور</span></a>
      <a href="../Reports/Report.php"><i class="fas fa-chart-bar"></i> <span>التقارير</span></a>
      <a href="../Login/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span></a>
    </nav>
  </aside>

  <div class="main-content">
    <div class="page-header">
      <h2><i class="fas fa-link"></i> ربط الطلاب بالأفواج</h2>
      <p>إدارة انتساب الطلاب إلى الأفواج التعليمية</p>
    </div>

    <div class="search-section">
      <div class="search-container">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="search" class="search-input" placeholder="ابحث عن طالب بالاسم، اللقب، أو رقم التسجيل...">
      </div>
    </div>

    <form id="studentsForm" class="main-form">
      <div class="form-section">
        <h3 class="section-title">
          <i class="fas fa-user-graduate"></i>
          قائمة الطلاب
        </h3>

        <div class="selection-counter">
          <span id="selectedCount">0</span> طالب محدد
        </div>

        <div id="students" class="students-grid">
          <!-- Students will be loaded here dynamically -->
        </div>
      </div>

      <div class="form-section">
        <h3 class="section-title">
          <i class="fas fa-users"></i>
          اختيار الفوج
        </h3>

        <div class="group-selection">
          <label for="group">اختر الفوج:</label>
          <select id="group" class="group-select">
            <?php foreach ($groups as $g): ?>
              <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="action-buttons">
        <button type="button" id="assignBtn" class="btn btn-primary">
          <i class="fas fa-link"></i>
          ربط الطلاب المحددين
        </button>
        <button type="button" id="unassignBtn" class="btn btn-secondary">
          <i class="fas fa-unlink"></i>
          إلغاء انتساب الطلاب
        </button>
      </div>
    </form>
  </div>

  <script src="Script.js"></script>
  <script>
    // Enhanced JavaScript for better user experience
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('search');
      const studentsContainer = document.getElementById('students');
      const selectedCount = document.getElementById('selectedCount');
      const assignBtn = document.getElementById('assignBtn');
      const unassignBtn = document.getElementById('unassignBtn');

      let selectedStudents = new Set();

      // Update selected count
      function updateSelectedCount() {
        selectedCount.textContent = selectedStudents.size;
        assignBtn.disabled = selectedStudents.size === 0;
        unassignBtn.disabled = selectedStudents.size === 0;
      }

      // Search functionality
      searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const studentCards = studentsContainer.querySelectorAll('.student-card');

        studentCards.forEach(card => {
          const studentName = card.querySelector('.student-name').textContent.toLowerCase();
          const studentId = card.querySelector('.student-id').textContent.toLowerCase();
          const isVisible = studentName.includes(searchTerm) || studentId.includes(searchTerm);
          card.style.display = isVisible ? 'block' : 'none';
        });
      });

      // Student selection
      studentsContainer.addEventListener('click', function(e) {
        const studentCard = e.target.closest('.student-card');
        if (studentCard) {
          const studentId = studentCard.dataset.studentId;

          if (selectedStudents.has(studentId)) {
            selectedStudents.delete(studentId);
            studentCard.classList.remove('selected');
          } else {
            selectedStudents.add(studentId);
            studentCard.classList.add('selected');
          }

          updateSelectedCount();
        }
      });

      // Select all functionality (optional enhancement)
      function selectAllStudents() {
        const studentCards = studentsContainer.querySelectorAll('.student-card');
        studentCards.forEach(card => {
          const studentId = card.dataset.studentId;
          selectedStudents.add(studentId);
          card.classList.add('selected');
        });
        updateSelectedCount();
      }

      // Deselect all functionality (optional enhancement)
      function deselectAllStudents() {
        const studentCards = studentsContainer.querySelectorAll('.student-card');
        studentCards.forEach(card => {
          const studentId = card.dataset.studentId;
          selectedStudents.delete(studentId);
          card.classList.remove('selected');
        });
        updateSelectedCount();
      }

      // Initialize buttons state
      updateSelectedCount();
    });
  </script>
</body>

</html>