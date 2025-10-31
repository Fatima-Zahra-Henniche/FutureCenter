<?php
include '../db_config.php';
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE etudiants SET nom=?, prenom=?, niveau=?, telephone=?, email=?, adresse=?, nom_parent=?, tel_parent=?, notes=? WHERE id=?");
    $stmt->bind_param(
        "sssssssssi",
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['niveau'],
        $_POST['telephone'],
        $_POST['email'],
        $_POST['adresse'],
        $_POST['nom_parent'],
        $_POST['tel_parent'],
        $_POST['notes'],
        $id
    );
    $stmt->execute();
    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM etudiants WHERE id=$id");
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تعديل الطالب</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>✏️ تعديل بيانات الطالب</h2>

    <form method="POST">
        <input type="text" name="nom" value="<?= $student['nom'] ?>" required>
        <input type="text" name="prenom" value="<?= $student['prenom'] ?>" required>
        <input type="text" name="niveau" value="<?= $student['niveau'] ?>">
        <input type="text" name="telephone" value="<?= $student['telephone'] ?>">
        <input type="email" name="email" value="<?= $student['email'] ?>">
        <textarea name="adresse"><?= $student['adresse'] ?></textarea>
        <input type="text" name="nom_parent" value="<?= $student['nom_parent'] ?>">
        <input type="text" name="tel_parent" value="<?= $student['tel_parent'] ?>">
        <textarea name="notes"><?= $student['notes'] ?></textarea>
        <button type="submit">💾 حفظ</button>
    </form>

</body>

</html>