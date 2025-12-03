<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sneaker Corner' ?></title>
    <link rel="stylesheet" href="/sneaker_corner/css/main.css">
    <link rel="stylesheet" href="/sneaker_corner/css/header.css">
    <link rel="stylesheet" href="/sneaker_corner/css/modals.css">
    <script src="/sneaker_corner/js/modals.js"></script>
    <?php if (!empty($extraCSS)): ?>
        <?php foreach ($extraCSS as $css): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (!empty($extraJS)): ?>
        <?php foreach ($extraJS as $js): ?>
            <link rel="script" href="<?= htmlspecialchars($js) ?>">
        <?php endforeach; ?>
    <?php endif; ?>

</head>
<body>
<div class="fixed">
    <header>
        <img id="logo" src="/sneaker_corner/images/logo.png"/>
        <p id="headerTitle">Sneaker Corner</p>
        <div id="navPages">
            <a class="navItem" href="/sneaker_corner/public/index.php">Home</a>
            <a class="navItem" href="/sneaker_corner/app/views/products.php">Products</a>
            <a class="navItem" href="/sneaker_corner/app/views/stores.php">Stores</a>
            <a class="navItem" href="/sneaker_corner/app/views/about.php">About</a>
            <a class="navItem" href="/sneaker_corner/app/views/contact.php">Contact</a>
        </div>
        <?php if (isset($_SESSION['name'])): ?>
            <div id="usernameContainer">
                <p id="username"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
                <div id="usernameDropdown">
                    <form method="POST" action="/sneaker_corner/app/views/auth/logout.php">
                        <button type="submit">Sign Out</button>
                    </form>
                </div>
            </div>

        <?php else: ?>
            <div id="navConnection">
                <button id="openLoginBtn" class="navItem">Login</button>
                <button id="openRegisterBtn" class="navItem">Sign Up</button>
            </div>
        <?php endif; ?>


    </header>
</div>
