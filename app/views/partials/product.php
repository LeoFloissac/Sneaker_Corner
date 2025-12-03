<!-- product.php -->
<div class="product">
    <div class="image-frame">
        <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" class="product-image"/>
    </div>
    <p class="product-title-price"><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php if (!empty($subtitle)): ?>
        <p class="product-subtitle"><?php echo htmlspecialchars($subtitle, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <p class="product-title-price"><?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?></p>
</div>
