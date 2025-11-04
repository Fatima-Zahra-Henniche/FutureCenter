<?php
// PHP code at the very top
include '../db_config.php';

// Get student ID from URL parameter
$id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare and execute update statement
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

    if ($stmt->execute()) {
        // Redirect to student list after successful update
        header("Location: index.php");
        exit;
    } else {
        $error_message = "⚠️ حدث خطأ أثناء تحديث البيانات. يرجى المحاولة مرة أخرى.";
    }
}

// Fetch student data
$result = $conn->query("SELECT * FROM etudiants WHERE id=$id");
$student = $result->fetch_assoc();

// Check if student exists
if (!$student) {
    $error_message = "⚠️ الطالب غير موجود.";
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات الطالب</title>
    <style>
        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #FFE1AF;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        /* Header styles */
        .header {
            background: linear-gradient(135deg, #E2B59A, #B77466);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h2 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            opacity: 0.9;
        }

        /* Form styles */
        .form-container {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .form-col {
            flex: 1;
            padding: 0 10px;
            min-width: 250px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border 0.3s, box-shadow 0.3s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #B77466;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .required::after {
            content: " *";
            color: #e74c3c;
        }

        /* Button styles */
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-primary {
            background: #B77466;
            color: white;
        }

        .btn-primary:hover {
            background: #E2B59A;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background: #B77466;
            color: white;
        }

        .btn-secondary:hover {
            background: #E2B59A;
        }

        .btn-icon {
            margin-left: 8px;
        }

        /* Alert styles */
        .alert {
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .alert-info {
            background: #FFE1AF;
            color: #2c3e50;
            border-left: 4px solid #a87d61ff;
        }

        .alert-error {
            background: #fadbd8;
            color: #c0392b;
            border-left: 4px solid #e74c3c;
        }

        .alert-icon {
            margin-left: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .form-col {
                width: 100%;
            }

            .button-group {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>✏️ تعديل بيانات الطالب</h2>
            <p>قم بتحديث معلومات الطالب في النموذج أدناه</p>
        </div>

        <div class="form-container">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">❌</span>
                    <?= $error_message ?>
                </div>
            <?php endif; ?>

            <?php if ($student): ?>
                <div class="alert alert-info">
                    <span class="alert-icon">ℹ️</span>
                    تقوم بتعديل بيانات الطالب: <strong><?= htmlspecialchars($student['nom'] . ' ' . $student['prenom']) ?></strong>
                </div>

                <form method="POST">
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="nom" class="required">الاسم</label>
                                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($student['nom']) ?>" required>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="prenom" class="required">اللقب</label>
                                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($student['prenom']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="niveau">المستوى</label>
                                <select id="niveau" name="niveau">
                                    <option value="">اختر المستوى</option>
                                    <option value="الابتدائي" <?= $student['niveau'] == 'الابتدائي' ? 'selected' : '' ?>>الابتدائي</option>
                                    <option value="متوسط" <?= $student['niveau'] == 'متوسط' ? 'selected' : '' ?>>متوسط</option>
                                    <option value="الثانوي" <?= $student['niveau'] == 'الثانوي' ? 'selected' : '' ?>>الثانوي</option>
                                    <option value="بكالوريا" <?= $student['niveau'] == 'بكالوريا' ? 'selected' : '' ?>>بكالوريا</option>
                                    <option value="جامعي" <?= $student['niveau'] == 'جامعي' ? 'selected' : '' ?>>جامعي</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="telephone">الهاتف</label>
                                <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($student['telephone']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="adresse">العنوان</label>
                        <textarea id="adresse" name="adresse"><?= htmlspecialchars($student['adresse']) ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-group">
                                <label for="nom_parent">اسم ولي الأمر</label>
                                <input type="text" id="nom_parent" name="nom_parent" value="<?= htmlspecialchars($student['nom_parent']) ?>">
                            </div>
                        </div>

                        <div class="form-col">
                            <div class="form-group">
                                <label for="tel_parent">هاتف ولي الأمر</label>
                                <input type="text" id="tel_parent" name="tel_parent" value="<?= htmlspecialchars($student['tel_parent']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">ملاحظات</label>
                        <textarea id="notes" name="notes"><?= htmlspecialchars($student['notes']) ?></textarea>
                    </div>

                    <div class="button-group">
                        <a href="index.php" class="btn btn-secondary">
                            <span class="btn-icon">↩️</span> رجوع
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <span class="btn-icon">💾</span> حفظ التغييرات
                        </button>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-error">
                    <span class="alert-icon">❌</span>
                    لا يمكن تحميل بيانات الطالب. يرجى التحقق من المعرف.
                </div>
                <div class="button-group">
                    <a href="index.php" class="btn btn-secondary">
                        <span class="btn-icon">↩️</span> رجوع إلى القائمة
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>