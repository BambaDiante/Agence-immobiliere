<?php
session_start();
require_once 'vendor/autoload.php';
require_once 'configuration/connexion.php'; 

// 1. On charge la config depuis le fichier LOCAL (exclu de Git)
$oauthConfigPath = __DIR__ . '/configuration/oauth.local.php';

if (!file_exists($oauthConfigPath)) {
    die("Erreur : Le fichier 'configuration/oauth.local.php' est introuvable. Créez-le à partir du modèle.");
}

$oauth = require $oauthConfigPath;

// Vérification que le fichier renvoie bien un tableau
if (!is_array($oauth)) {
    die("Erreur : Le fichier oauth.local.php doit retourner un tableau (return [...];).");
}

$clientID     = $oauth['client_id'] ?? '';
$clientSecret = $oauth['client_secret'] ?? '';
$redirectUri  = $oauth['redirect_uri'] ?? 'http://localhost/agenceimm/verif.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

// Désactiver la vérification SSL (uniquement pour le développement local sous WAMP)
$client->setHttpClient(new \GuzzleHttp\Client([
    'verify' => false,
]));

if (isset($_GET['error'])) {
    die('Erreur Google OAuth : ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));
}

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $email = $google_account_info->email;
        $name = $google_account_info->name;        

        $stmt = $pdo->prepare("SELECT * FROM users WHERE mail = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['connected'] = true;
            $_SESSION['id'] = $user['IdUser']; // Note : Vérifie si c'est 'id' ou 'IdUser' dans tes autres pages
            $_SESSION['nom'] = $user['nom'];
            header("Location: commerciaux/acceuil.php");
            exit();
        } else {
            $insert = $pdo->prepare("INSERT INTO users (nom, mail, type, is_activated) VALUES (?, ?, 'Client', 1)");
            $insert->execute([$name, $email]);
            
            $_SESSION['connected'] = true;
            $_SESSION['id'] = $pdo->lastInsertId();
            $_SESSION['nom'] = $name;
            header("Location: commerciaux/acceuil.php");
            exit();
        }
    } else {
        die('Erreur token Google : ' . htmlspecialchars($token['error_description'] ?? $token['error'], ENT_QUOTES, 'UTF-8'));
    }
} else {
    die('Aucun code OAuth reçu. Redirection vers Google échouée.');
}