<?php 
    // Load controller and get product data - config.php is already loaded by auth_handler.php via header.php
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../controllers/ProductsController.php';

    $extraCSS = ["/sneaker_corner/css/product-detail.css"];
    include 'partials/header.php'; 

    $controller = new ProductsController($conn ?? null);
    
    // Support slugs (priority) or ID (fallback)
    $productSlug = isset($_GET['slug']) ? trim($_GET['slug']) : null;
    $colorSlug = isset($_GET['color']) ? trim($_GET['color']) : null;
    $variantId = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if ($productSlug && $colorSlug) {
        $product = $controller->show(null, $productSlug, $colorSlug);
    } else {
        $product = $controller->show($variantId);
    }

    if (!$product) {
        echo '<main class="product-detail-page"><div class="container"><h1>Product not found</h1><p>This product does not exist or is no longer available.</p><a href="/sneaker_corner/app/views/products.php" class="btn-back">← Back to products</a></div></main>';
        include 'partials/footer.php';
        exit;
    }
?>

<main class="product-detail-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="/sneaker_corner/public/index.php">Home</a>
            <span>/</span>
            <a href="/sneaker_corner/app/views/products.php">Products</a>
            <span>/</span>
            <span class="current"><?php echo htmlspecialchars($product['name']); ?></span>
        </nav>

        <div class="product-detail">
            <!-- Image gallery -->
            <div class="product-gallery">
                <div class="gallery-thumbnails">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <button class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>" 
                                data-index="<?php echo $index; ?>"
                                onclick="changeImage(<?php echo $index; ?>)">
                            <img src="<?php echo htmlspecialchars($image['url']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['alt']); ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
                <div class="gallery-main">
                    <img id="main-image" 
                         src="<?php echo htmlspecialchars($product['images'][0]['url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['images'][0]['alt']); ?>">
                </div>
            </div>

            <!-- Product information -->
            <div class="product-info">
                <div class="product-header">
                    <p class="product-brand"><?php echo htmlspecialchars($product['brand']); ?></p>
                    <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="product-subtitle"><?php echo htmlspecialchars($product['model'] ?? ''); ?></p>
                </div>

                <p class="product-price"><?php echo htmlspecialchars($product['price']); ?></p>

                <!-- Color selector -->
                <?php if (count($product['colors']) > 1): ?>
                <div class="product-colors">
                    <p class="option-label">Color: <strong><?php echo htmlspecialchars($product['color']); ?></strong></p>
                    <div class="color-options">
                        <?php foreach ($product['colors'] as $color): ?>
                            <a href="?slug=<?php echo htmlspecialchars($product['product_slug']); ?>&amp;color=<?php echo htmlspecialchars($color['slug']); ?>" 
                               class="color-option <?php echo $color['is_current'] ? 'active' : ''; ?>"
                               title="<?php echo htmlspecialchars($color['name']); ?>">
                                <img src="<?php echo htmlspecialchars($color['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($color['name']); ?>">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Size selector -->
                <?php if (!empty($product['sizes'])): ?>
                <div class="product-sizes">
                    <div class="size-header">
                        <p class="option-label">Available sizes</p>
                    </div>
                    <div class="size-options">
                        <?php foreach ($product['sizes'] as $size): ?>
                            <span class="size-option <?php echo $size['stock_quantity'] <= 0 ? 'out-of-stock' : ''; ?>">
                                <?php echo htmlspecialchars($size['size']); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Store availability -->
                <?php if (!empty($product['stores'])): ?>
                <div class="product-stores">
                    <p class="option-label">Available in store</p>
                    <div class="store-list">
                        <?php foreach ($product['stores'] as $store): ?>
                            <div class="store-item">
                                <div class="store-info">
                                    <strong><?php echo htmlspecialchars($store['name']); ?></strong>
                                    <span><?php echo htmlspecialchars($store['address']); ?>, <?php echo htmlspecialchars($store['postal_code'] . ' ' . $store['city']); ?></span>
                                    <?php if (!empty($store['phone'])): ?>
                                        <span class="store-phone"><?php echo htmlspecialchars($store['phone']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo htmlspecialchars($store['maps_url']); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="store-map-link">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    View on Maps
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Back link -->
                <a href="/sneaker_corner/app/views/products.php" class="btn-back">← Back to products</a>
            </div>
        </div>
    </div>
</main>

<script>
// Image data for JS
const images = <?php echo json_encode($product['images']); ?>;

// Change main image
function changeImage(index) {
    const mainImage = document.getElementById('main-image');
    mainImage.src = images[index].url;
    mainImage.alt = images[index].alt;
    
    // Update active thumbnails
    document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
        thumb.classList.toggle('active', i === index);
    });
}
</script>

<?php include 'partials/footer.php'; ?>
