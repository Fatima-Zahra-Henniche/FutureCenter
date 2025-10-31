<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>التقارير والإحصائيات</title>
    <link rel="stylesheet" href="Reports.css">
</head>

<body>
    <aside class="sidebar">
        <?php include_once("../includes/SideBar.php"); ?>
    </aside>

    <div class="container">
        <h1>التقارير والإحصائيات</h1>

        <!-- 🔍 مربع البحث عن طالب -->
        <div class="search-group">
            <h3>بحث عن طالب</h3>
            <div class="search-controls">
                <input type="text" id="search-input" placeholder="اكتب اسم الطالب أو اللقب أو رقم التسجيل...">
                <label class="checkbox-container">
                    <input type="checkbox" id="exact-match">
                    <span class="checkmark"></span>
                    بحث متطابق
                </label>
            </div>
        </div>

        <!-- 📅 فلاتر التاريخ -->
        <div class="filter-group">
            <h3>فلاتر البحث</h3>
            <div class="filter-controls">
                <div class="date-input">
                    <label for="date-from">من التاريخ:</label>
                    <input type="date" id="date-from">
                </div>
                <div class="date-input">
                    <label for="date-to">إلى التاريخ:</label>
                    <input type="date" id="date-to">
                </div>
                <button id="filter-btn" class="btn-primary">تطبيق الفلتر</button>
            </div>
        </div>

        <!-- 🗂️ التبويبات -->
        <div class="tabs-container">
            <div class="tab-headers">
                <button class="tab-header active" data-tab="attendance">تقرير الحضور (0)</button>
                <button class="tab-header" data-tab="payments">تقرير المدفوعات (0)</button>
                <button class="tab-header" data-tab="students">تقرير الطلاب (0)</button>
            </div>

            <div class="tab-content active" id="attendance-tab">
                <h3>تقرير الحضور والغياب للطلاب</h3>
                <div class="table-container">
                    <table id="attendance-table" class="data-table">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>الفوج</th>
                                <th>رقم التسجيل</th>
                                <th>الطالب</th>
                                <th>من</th>
                                <th>إلى</th>
                                <th>الحالة</th>
                                <th>المبلغ المخصوم</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- سيتم ملؤه بالبيانات من JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content" id="payments-tab">
                <h3>تقرير المدفوعات والرصيد</h3>
                <div class="table-container">
                    <table id="payments-table" class="data-table">
                        <thead>
                            <tr>
                                <th>التاريخ</th>
                                <th>رقم التسجيل</th>
                                <th>الطالب</th>
                                <th>المبلغ</th>
                                <th>نوع الدفع</th>
                                <th>ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- سيتم ملؤه بالبيانات من JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-content" id="students-tab">
                <h3>تقرير الطلاب والأفواج</h3>
                <div class="table-container">
                    <table id="students-table" class="data-table">
                        <thead>
                            <tr>
                                <th>رقم التسجيل</th>
                                <th>الاسم</th>
                                <th>اللقب</th>
                                <th>المستوى</th>
                                <th>تاريخ التسجيل</th>
                                <th>عدد الأفواج</th>
                                <th>الرصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- سيتم ملؤه بالبيانات من JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="Reports.js"></script>
</body>

</html>