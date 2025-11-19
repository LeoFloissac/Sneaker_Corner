<!-- header.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'Sneaker Corner' ?></title>
  <link rel="stylesheet" href="/my_website/css/main.css">
  <link rel="stylesheet" href="/my_website/css/header.css">
  <link rel="stylesheet" href="/my_website/css/modals.css">
  <script src="/my_website/js/modals.js"></script>
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
<?php
session_start();
?>

<div class="fixed">
    <header>
        <img id="logo" src="/my_website/images/logo.png"/>
        <p id="headerTitle">Sneaker Corner</p>
        <div id="navPages">
            <a class="navItem" href="/my_website/public/index.php">Home</a>
            <a class="navItem" href="/my_website/app/products.php">Products</a>
            <a class="navItem" href="/my_website/app/stores.php">Stores</a>
            <a class="navItem" href="/my_website/app/about.php">About</a>
            <a class="navItem" href="/my_website/app/contact.php">Contact</a>
        </div>
        <?php if (isset($_SESSION['name'])): ?>
            <div id="usernameContainer">
                <p id="username"><?php echo htmlspecialchars($_SESSION['name']); ?></p>
                <div id="usernameDropdown">
                    <form method="POST" action="/my_website/app/auth/logout.php">
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
