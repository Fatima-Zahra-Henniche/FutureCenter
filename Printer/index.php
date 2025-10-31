<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إيصال الدفع</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="receipt">
        <img src="logo.png" class="logo" alt="Logo">

        <h2>مركز المستقبل للتعليم و اللغات</h2>
        <h3>Future Centre for Training</h3>

        <hr>

        <div class="info">
            <p>التاريخ: <span id="date"></span></p>
            <p>رقم التسجيل: <span id="student-id">1234</span></p>
            <p>الطالب: <span id="student-name">محمد أحمد</span></p>
            <p>الفوج: <span id="group-name">فوج 1</span></p>
            <p>المبلغ المدفوع: <span id="amount">2000.00 دج</span></p>
            <p>المستحقات الباقية: <span id="remaining">500.00 دج</span></p>
        </div>

        <hr>

        <div class="footer">
            <p>منطقة أ1 رقم 12 طريق تنس بالقرب من الحماية المدنية</p>
            <p>الطابق الثاني - الشطية - الشلف</p>
            <p>futur.center.for.training@gmail.com</p>
            <p>0799935885 / 0659610008</p>
            <p><strong>أبناؤكم أمانة</strong></p>
        </div>

        <button onclick="window.print()" class="print-btn">🖨️ طباعة الإيصال</button>
    </div>

    <script>
        // وضع التاريخ تلقائيًا
        const now = new Date();
        const formatted = now.getFullYear() + '/' +
            String(now.getMonth() + 1).padStart(2, '0') + '/' +
            String(now.getDate()).padStart(2, '0') + ' ' +
            String(now.getHours()).padStart(2, '0') + ':' +
            String(now.getMinutes()).padStart(2, '0');
        document.getElementById('date').textContent = formatted;
    </script>
</body>

</html>