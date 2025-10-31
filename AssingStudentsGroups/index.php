<?php include "load_data.php"; ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>ربط الطلاب بالأفواج</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>ربط الطلاب بالأفواج</h2>

  <input type="text" id="search" placeholder="ابحث عن طالب بالاسم أو اللقب">

  <form id="studentsForm">
    <div id="students"></div>

    <label>اختر الفوج:</label>
    <select id="group">
      <?php foreach ($groups as $g): ?>
        <option value="<?= $g['id'] ?>"><?= $g['nom'] ?></option>
      <?php endforeach; ?>
    </select>

    <div class="buttons">
      <button type="button" id="assignBtn">ربط الطلاب بالفوج</button>
      <button type="button" id="unassignBtn">إلغاء انتساب الطالب</button>
    </div>
  </form>

  <script src="script.js"></script>
</body>
</html>
