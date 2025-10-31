<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تسجيل الحضور</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>📅 تسجيل الحضور</h2>

    <div class="section">
        <label>التاريخ:</label>
        <input type="date" id="date" value="<?= date('Y-m-d') ?>">
        <label>من:</label>
        <input type="time" id="time_start" value="08:00">
        <label>إلى:</label>
        <input type="time" id="time_end" value="10:00">
    </div>

    <div class="section">
        <label>الفوج:</label>
        <select id="group"></select>
    </div>

    <div class="section">
        <h3>قائمة الطلاب</h3>
        <div id="students"></div>
        <button id="saveBtn">💾 حفظ الحضور وخصم السعر</button>
    </div>

    <div class="section">
        <h3>📋 تقرير الحضور</h3>
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
            <tbody></tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>

</html>