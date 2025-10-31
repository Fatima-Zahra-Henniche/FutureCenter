<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO etudiants 
        (id_etudiant, nom, prenom, date_naissance, niveau, telephone, email, adresse, nom_parent, tel_parent, notes, date_inscription, actif)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1)");

    $stmt->bind_param(
        "sssssssssss",
        $_POST['id_etudiant'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['date_naissance'],
        $_POST['niveau'],
        $_POST['telephone'],
        $_POST['email'],
        $_POST['adresse'],
        $_POST['nom_parent'],
        $_POST['tel_parent'],
        $_POST['notes']
    );

    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit;
