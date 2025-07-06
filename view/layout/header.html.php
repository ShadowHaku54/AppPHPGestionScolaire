<?php

use App\Config\View;
use App\Model\Etudiant;
use App\Model\Utilisateur;

/** @var Utilisateur|Etudiant $utilisateur */
$utilisateur = $_SESSION['utilisateur'] ?? null;
$role = $utilisateur->getRole() ?? null;

$onglets = $role ? View::ongletsVisibles($role) : [];

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Stylesheets here -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/layout/header.css">
    <link rel="stylesheet" href="./css/layout/footer.css">
    <link rel="stylesheet" href="./css/layout/notification.css">
    <link rel="stylesheet" href="./css/layout/errors.css">

    <?php
    if (!empty($pathCSS)) {
        echo "<link rel='stylesheet' href='./css/" . htmlspecialchars($pathCSS) . "'>\n";
    }
    ?>

    <!-- Scripts here -->
    <script src="./js/script.js" defer></script>
    <script src="./js/layout/header.js" defer></script>
    <script src="./js/layout/footer.js" defer></script>
    <script src="./js/layout/notifications.js" defer></script>

    <?php
    if (!empty($pathJS)) {
        echo "<script src='./js/" . htmlspecialchars($pathJS) . "' defer></script>\n";
    }
    ?>


</head>
<body>
<header id="mainHeader">
    <a href="?controller=utilisateur&action=show-home" class="logo-area">
        <img src="./images/logo-ism.svg" alt="Logo ISM">
        <div class="logo-sep"></div>
        <span>Institut Supérieur de Management</span>
    </a>
    <nav>
        <ul>
            <?php foreach ($onglets as $o): ?>
                <li>
                    <a href="<?= $o['href'] ?>"
                       class="<?= View::estActif($o['href']) ? 'active' : '' ?>">
                        <i class="fa-solid <?= $o['icon'] ?>"></i>
                        <?= $o['label'] ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </nav>
    <div class="user-area" id="userArea">
        <span class="notif"><i class="fa-solid fa-bell"></i></span>
        <div class="avatar-btn">
            <div class="avatar <?= $role->value ?>"><?= $role->value ?></div>
            <i class="fa-solid fa-chevron-down"></i>
        </div>
        <div class="user-menu" id="userMenu">
            <a href="?controller=utilisateur&action=show-home">Mon profil</a>
            <a href="?controller=utilisateur&action=logout">Déconnexion</a>
        </div>
    </div>
</header>
<main>