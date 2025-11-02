<?php
// Start session
session_start();

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username === "admin" && $password === "1234") {
        $_SESSION["logged_in"] = true;
        header("Location: ../Dashbord/Dashbord.php");
        exit();
    } else {
        $error = "اسم المستخدم أو كلمة المرور غير صحيحة";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">
        <img src="../img/logo.png" alt="Logo" class="logo">
        <h2>تسجيل الدخول</h2>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم" required>
            <input type="password" name="password" id="password" placeholder="كلمة المرور" required>
            <button type="submit">دخول</button>
        </form>
    </div>

    <script>
        const toggleBtn = document.getElementById('togglePass');
        const passwordInput = document.getElementById('password');
        toggleBtn.onclick = () => {
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        };
    </script>

</body>

</html>