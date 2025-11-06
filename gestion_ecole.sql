-- --------------------------------------------------------
-- Base de données : gestion_ecole
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS gestion_ecole CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE gestion_ecole;
-- --------------------------------------------------------
-- Table : etudiants
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS etudiants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_etudiant VARCHAR(50) UNIQUE NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE,
    niveau VARCHAR(50),
    telephone VARCHAR(50),
    email VARCHAR(100),
    adresse TEXT,
    nom_parent VARCHAR(100),
    tel_parent VARCHAR(50),
    date_inscription DATE,
    notes TEXT,
    actif BOOLEAN DEFAULT TRUE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Table : groupes
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS groupes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    prix_seance DOUBLE NOT NULL,
    capacite_max INT DEFAULT 20,
    jour_cours VARCHAR(100),
    heure_debut VARCHAR(20),
    heure_fin VARCHAR(20),
    actif BOOLEAN DEFAULT TRUE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Table : etudiants_groupes
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS etudiants_groupes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    groupe_id INT,
    date_inscription DATE,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (groupe_id) REFERENCES groupes(id) ON DELETE CASCADE,
    UNIQUE(etudiant_id, groupe_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Table : paiements
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    montant DOUBLE NOT NULL,
    date_paiement DATE,
    type_paiement VARCHAR(50) DEFAULT 'Espèces',
    notes TEXT,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Table : presence
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS presence (
    id INT AUTO_INCREMENT PRIMARY KEY,
    etudiant_id INT,
    groupe_id INT,
    date DATE,
    time_start VARCHAR(20),
    time_end VARCHAR(20),
    statut VARCHAR(50),
    montant_debite DOUBLE DEFAULT 0,
    notes TEXT,
    FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
    FOREIGN KEY (groupe_id) REFERENCES groupes(id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- --------------------------------------------------------
-- Procédures et vues correspondant aux fonctions Python
-- --------------------------------------------------------
-- 1️⃣ Ajouter un paiement
DELIMITER // CREATE PROCEDURE add_payment(IN p_etudiant_id INT, IN p_montant DOUBLE) BEGIN
INSERT INTO paiements (etudiant_id, montant, date_paiement)
VALUES (p_etudiant_id, p_montant, CURDATE());
END // DELIMITER;
-- 2️⃣ Calcul du solde de l'étudiant
DELIMITER // CREATE FUNCTION get_balance(p_etudiant_id INT) RETURNS DOUBLE DETERMINISTIC BEGIN
DECLARE total_payments DOUBLE DEFAULT 0;
DECLARE total_debited DOUBLE DEFAULT 0;
DECLARE solde DOUBLE DEFAULT 0;
SELECT IFNULL(SUM(montant), 0) INTO total_payments
FROM paiements
WHERE etudiant_id = p_etudiant_id;
SELECT IFNULL(SUM(montant_debite), 0) INTO total_debited
FROM presence
WHERE etudiant_id = p_etudiant_id;
SET solde = total_payments - total_debited;
RETURN solde;
END // DELIMITER;
-- 3️⃣ Débiter un montant lors de la présence
DELIMITER // CREATE PROCEDURE debit_for_presence(
    IN p_etudiant_id INT,
    IN p_groupe_id INT,
    IN p_prix DOUBLE
) BEGIN
INSERT INTO presence (
        etudiant_id,
        groupe_id,
        date,
        statut,
        montant_debite
    )
VALUES (
        p_etudiant_id,
        p_groupe_id,
        CURDATE(),
        'Present',
        p_prix
    );
END // DELIMITER;
-- 4️⃣ Historique des paiements d’un étudiant
CREATE OR REPLACE VIEW v_student_payments AS
SELECT e.id AS etudiant_id,
    e.nom,
    e.prenom,
    p.date_paiement,
    p.montant,
    p.type_paiement,
    p.notes
FROM paiements p
    JOIN etudiants e ON p.etudiant_id = e.id
ORDER BY p.date_paiement DESC;
-- 5️⃣ Liste des groupes d’un étudiant
CREATE OR REPLACE VIEW v_student_groups AS
SELECT e.id AS etudiant_id,
    g.id AS groupe_id,
    g.nom AS groupe_nom,
    g.prix_seance
FROM etudiants e
    JOIN etudiants_groupes eg ON e.id = eg.etudiant_id
    JOIN groupes g ON g.id = eg.groupe_id
WHERE g.actif = TRUE;
-- 6️⃣ Liste des étudiants d’un groupe
CREATE OR REPLACE VIEW v_group_students AS
SELECT g.id AS groupe_id,
    e.id AS etudiant_id,
    e.nom,
    e.prenom
FROM groupes g
    JOIN etudiants_groupes eg ON g.id = eg.groupe_id
    JOIN etudiants e ON e.id = eg.etudiant_id
WHERE e.actif = TRUE;
-- 7️⃣ Historique de présence d’un étudiant
CREATE OR REPLACE VIEW v_student_attendance AS
SELECT p.etudiant_id,
    p.date,
    g.nom AS groupe_nom,
    p.statut,
    p.montant_debite
FROM presence p
    JOIN groupes g ON p.groupe_id = g.id
ORDER BY p.date DESC;
-- 8️⃣ Nombre d’étudiants par niveau
CREATE OR REPLACE VIEW v_student_count_by_level AS
SELECT niveau,
    COUNT(*) AS total
FROM etudiants
WHERE actif = TRUE
GROUP BY niveau;
-- 9️⃣ Historique des paiements d’un étudiant avec filtrage de dates
DELIMITER // CREATE PROCEDURE get_student_payment_history(
    IN p_etudiant_id INT,
    IN p_date_from DATE,
    IN p_date_to DATE
) BEGIN IF p_date_from IS NULL
OR p_date_to IS NULL THEN
SELECT date_paiement,
    montant,
    type_paiement,
    notes
FROM paiements
WHERE etudiant_id = p_etudiant_id
ORDER BY date_paiement DESC;
ELSE
SELECT date_paiement,
    montant,
    type_paiement,
    notes
FROM paiements
WHERE etudiant_id = p_etudiant_id
    AND date_paiement BETWEEN p_date_from AND p_date_to
ORDER BY date_paiement DESC;
END IF;
END // DELIMITER;