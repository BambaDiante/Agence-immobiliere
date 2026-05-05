<?php
require_once "./configuration/connexion.php";

try {
    // 1. On récupère tous les utilisateurs
    $query = $pdo->query("SELECT IdUser, password FROM users");
    $utilisateurs = $query->fetchAll(PDO::FETCH_ASSOC);

    echo "Démarrage de la mise à jour...<br>";

    foreach ($utilisateurs as $user) {
        $id = $user['IdUser'];
        $mdpActuel = $user['password'];

        // On vérifie si le mot de passe n'est pas déjà haché
        // Un hash password_hash commence toujours par '$2y$'
        if (strpos($mdpActuel, '$2y$') !== 0) {
            
            // Hachage du mot de passe en clair (ou vide)
            $nouveauHash = password_hash($mdpActuel, PASSWORD_DEFAULT);

            // Mise à jour dans la base
            $update = $pdo->prepare("UPDATE users SET password = :pass WHERE IdUser = :id");
            $update->execute([
                ':pass' => $nouveauHash,
                ':id' => $id
            ]);

            echo "Utilisateur ID $id mis à jour.<br>";
        } else {
            echo "Utilisateur ID $id déjà haché, ignoré.<br>";
        }
    }

    echo "<strong>Terminé !</strong> N'oublie pas de supprimer ce fichier après usage.";

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>