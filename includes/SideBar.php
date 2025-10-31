<!-- includes/sidebar.php -->
<aside class="sidebar">
    <h2>Gestion École</h2>
    <nav class="menu">
        <a href="../Dashbord/Dashbord.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Dashbord.php' ? 'active' : '' ?>">📊 لوحة التحكم</a>
        <a href="../Students/AddStudent.php" class="<?= basename($_SERVER['PHP_SELF']) == 'AddStudent.php' ? 'active' : '' ?>">👨‍🎓 الطلاب</a>
        <a href="../Groups/Groups.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Groups.php' ? 'active' : '' ?>">👥 الأفواج</a>
        <a href="../AssingStudentsGroups/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], 'AssingStudentsGroups') ? 'active' : '' ?>">🔗 ربط الطلاب بالأفواج</a>
        <a href="../Presence/index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' && strpos($_SERVER['PHP_SELF'], 'Presence') ? 'active' : '' ?>">🕒 الحضور</a>
        <a href="../Reports/Report.php" class="<?= basename($_SERVER['PHP_SELF']) == 'Report.php' ? 'active' : '' ?>">📈 التقارير</a>
        <a href="../Login/logout.php" style="margin-top:auto;background:#d32f2f;">🚪 تسجيل الخروج</a>
    </nav>
</aside>

<style>
    .sidebar {
        width: 240px;
        background: linear-gradient(180deg, #673ab7, #512da8);
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        padding-top: 20px;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
        height: 100vh;
        position: fixed;
        right: 0;
        top: 0;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 0 10px;
    }

    .menu a {
        text-decoration: none;
        color: white;
        padding: 10px 15px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .menu a:hover,
    .menu a.active {
        background: rgba(255, 255, 255, 0.2);
    }
</style>