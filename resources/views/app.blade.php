<?php
    require_once 'config.php';
    require_once 'includes/header.php';

if (empty($_GET) && empty($_POST)) {
    // Load main page
        require_once 'pages/mainPage.php';
    // load end
}elseif (isset($_GET["page"]) || isset($_POST["page"])) {
    // Load requested page
    $page = $_GET["page"] ?? $_POST["page"];

    switch ($page) {
        case 'search':
            header("Location: " . '/search');
            break;
        case 'login':
            header("Location: " . '/loggin');
            break;
        default:
            header("Location: " . '/');
            break;
    }
    // load end
}