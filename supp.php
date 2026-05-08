<?php
require_once "./configuration/connexion.php"; // Ton fichier de connexion PDO

try {
    // 1. Désactiver les contraintes de clés étrangères pour éviter les erreurs
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 2. Récupérer la liste de toutes les tables de la base
    $tablesQuery = $pdo->query("SHOW TABLES");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        // 3. Vider la table et remettre l'auto-incrément à zéro
        $pdo->exec("TRUNCATE TABLE `$table`");
        echo "Table vider : $table <br>";
    }

    // 4. Réactiver les contraintes
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "<strong>La base de données a été vidée avec succès.</strong>";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>