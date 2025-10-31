<?php include '../db_config.php'; ?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>الطلاب</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <aside class="sidebar">
        <?php include_once("../includes/SideBar.php"); ?>
    </aside>
    <h2>📚 إدارة الطلاب</h2>

    <form action="add_student.php" method="POST">
        <h3>➕ إضافة طالب</h3>
        <input type="text" name="id_etudiant" placeholder="رقم التسجيل" required>
        <input type="text" name="nom" placeholder="الاسم" required>
        <input type="text" name="prenom" placeholder="اللقب" required>
        <input type="date" name="date_naissance" required>
        <select name="niveau" required>
            <option value="">اختر المستوى</option>
            <option>الابتدائي</option>
            <option>متوسط</option>
            <option>الثانوي</option>
            <option>بكالوريا</option>
            <option>جامعي</option>
        </select>
        <input type="text" name="telephone" placeholder="الهاتف">
        <input type="email" name="email" placeholder="البريد الإلكتروني">
        <input type="text" name="nom_parent" placeholder="اسم ولي الأمر">
        <input type="text" name="tel_parent" placeholder="هاتف ولي الأمر">
        <textarea name="adresse" placeholder="العنوان"></textarea>
        <textarea name="notes" placeholder="ملاحظات"></textarea>
        <button type="submit">إضافة</button>
    </form>

    <hr>

    <h3>📋 قائمة الطلاب</h3>
    <table border="1" cellpadding="6">
        <tr>
            <th>رقم التسجيل</th>
            <th>الاسم</th>
            <th>اللقب</th>
            <th>المستوى</th>
            <th>الهاتف</th>
            <th>الإجراءات</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM etudiants ORDER BY nom, prenom");
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $row['id_etudiant'] ?></td>
                <td><?= $row['nom'] ?></td>
                <td><?= $row['prenom'] ?></td>
                <td><?= $row['niveau'] ?></td>
                <td><?= $row['telephone'] ?></td>
                <td>
                    <a href="EditStudent.php?id=<?= $row['id'] ?>">✏️ تعديل</a> |
                    <a href="DeleteStudent.php?id=<?= $row['id'] ?>" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑️ حذف</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</body>

</html>