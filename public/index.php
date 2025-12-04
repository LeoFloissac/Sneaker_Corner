<?php
/**
 * Home Page - Sneaker Corner
 */

// Load configuration (will be included by auth_handler.php via header.php, but we need it here too)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/controllers/ProductsController.php';

// Initialize controller with mysqli connection
$productsController = new ProductsController($conn ?? null);

// Get all products and shuffle them for random order
$allProducts = $productsController->index();
shuffle($allProducts);

// Get featured products (first 8 from shuffled array)
$featuredProducts = array_slice($allProducts, 0, 8);

// Get new arrivals (next 4 from shuffled array, different from featured)
$newArrivals = array_slice($allProducts, 8, 4);

// Available brands with logo files
$brands = [
    ['name' => 'Nike', 'logo' => 'nike.png'],
    ['name' => 'Adidas', 'logo' => 'adidas.png'],
    ['name' => 'Puma', 'logo' => 'puma.png'],
    ['name' => 'New Balance', 'logo' => 'new-balance.png'],
    ['name' => 'Converse', 'logo' => 'converse.png'],
    ['name' => 'Vans', 'logo' => 'vans.png']
];

// Extra CSS (array format for header.php)
$extraCSS = [
    '/sneaker_corner/css/product.css',
    '/sneaker_corner/css/home.css'
];

include __DIR__ . '/../app/views/partials/header.php';
?>

<main class="home-page">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-background"></div>
        <div class="hero-content">
            <span class="hero-badge">🇻🇳 Vietnam's Premier Sneaker Destination</span>
            <h1>
                <span>Step Into</span>
                <span>Your Style</span>
            </h1>
            <p class="hero-subtitle">
                Discover the latest sneaker collections from the world's top brands. 
                Guaranteed authenticity, premium service.
            </p>
            <div class="hero-buttons">
                <a href="/sneaker_corner/app/views/products.php" class="btn btn-white">
                    Shop Collection
                </a>
                <a href="/sneaker_corner/app/views/stores.php" class="btn btn-outline-white">
                    Find a Store
                </a>
            </div>
        </div>
        <svg class="hero-decoration" viewBox="0 0 100 100" fill="none">
            <circle cx="50" cy="50" r="45" stroke="white" stroke-width="0.5"/>
            <circle cx="50" cy="50" r="35" stroke="white" stroke-width="0.3"/>
            <circle cx="50" cy="50" r="25" stroke="white" stroke-width="0.2"/>
        </svg>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-section">
        <div class="page-container">
            <div class="section-header">
                <h2>Popular Right Now</h2>
                <a href="/sneaker_corner/app/views/products.php" class="btn btn-dark">
                    View All
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="products-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <?php
                        $variantId = $product['variant_id'];
                        $productSlug = $product['product_slug'];
                        $colorSlug = $product['color_slug'];
                        $title = $product['title'];
                        $subtitle = $product['subtitle'];
                        $price = $product['price'];
                        $img = $product['img'];
                        include __DIR__ . '/../app/views/partials/product.php';
                    ?>
                <?php endforeach; ?>
                
                <?php if (empty($featuredProducts)): ?>
                    <p style="grid-column: 1/-1; text-align: center; color: var(--medium-gray);">
                        No products available at this time.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="page-container">
            <div class="section-header">
                <h2>Shop by Style</h2>
            </div>
            <div class="categories-grid">
                <a href="/sneaker_corner/app/views/products.php" class="category-card">
                    <img src="/sneaker_corner/images/categories/lifestyle.jpeg" alt="Lifestyle" onerror="this.style.display='none'">
                    <div class="category-content">
                        <h3>Lifestyle</h3>
                        <p>Everyday essentials for any occasion</p>
                    </div>
                </a>
                <a href="/sneaker_corner/app/views/products.php" class="category-card">
                    <img src="/sneaker_corner/images/categories/running.png" alt="Running" onerror="this.style.display='none'">
                    <div class="category-content">
                        <h3>Running</h3>
                        <p>Performance meets comfort</p>
                    </div>
                </a>
                <a href="/sneaker_corner/app/views/products.php" class="category-card">
                    <img src="/sneaker_corner/images/categories/basketball.jpeg" alt="Basketball" onerror="this.style.display='none'">
                    <div class="category-content">
                        <h3>Basketball</h3>
                        <p>Court-ready icons</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="new-arrivals-section">
        <div class="page-container">
            <div class="section-header">
                <h2>New Arrivals</h2>
                <a href="/sneaker_corner/app/views/products.php" class="btn btn-dark">
                    Shop New
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <div class="products-grid four-cols">
                <?php foreach ($newArrivals as $product): ?>
                    <?php
                        $variantId = $product['variant_id'];
                        $productSlug = $product['product_slug'];
                        $colorSlug = $product['color_slug'];
                        $title = $product['title'];
                        $subtitle = $product['subtitle'];
                        $price = $product['price'];
                        $img = $product['img'];
                        include __DIR__ . '/../app/views/partials/product.php';
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="brands-section">
        <div class="page-container">
            <h2>Our Brands</h2>
            <div class="brands-grid">
                <?php foreach ($brands as $brand): ?>
                    <a href="/sneaker_corner/app/views/products.php?brand=<?php echo urlencode($brand['name']); ?>" class="brand-item">
                        <img src="/sneaker_corner/images/brands/<?php echo htmlspecialchars($brand['logo']); ?>" 
                             alt="<?php echo htmlspecialchars($brand['name']); ?>"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <span class="brand-name" style="display: none;"><?php echo htmlspecialchars($brand['name']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="why-us-section">
        <div class="page-container">
            <h2>Why Sneaker Corner?</h2>
            <div class="why-us-grid">
                <div class="why-us-item">
                    <div class="why-us-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3>100% Authentic</h3>
                    <p>All products are certified authentic, directly from official brand partners.</p>
                </div>
                <div class="why-us-item">
                    <div class="why-us-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3>3 Stores in Vietnam</h3>
                    <p>Find us in Ho Chi Minh City, Hanoi, Da Nang, and more locations.</p>
                </div>
                <div class="why-us-item">
                    <div class="why-us-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3>Latest Releases</h3>
                    <p>Get early access to the newest drops and exclusive releases.</p>
                </div>
                <div class="why-us-item">
                    <div class="why-us-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3>Premium Service</h3>
                    <p>Our sneaker experts help you find your perfect pair.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="page-container">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h2>Stay in the Loop</h2>
                    <p>Be the first to know about new arrivals, exclusive releases, and special events.</p>
                </div>
                <form class="newsletter-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-white">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="page-container">
            <div class="cta-content">
                <h2>Ready to Find Your Pair?</h2>
                <p>Visit one of our stores in Vietnam to discover our complete collection.</p>
                <a href="/sneaker_corner/app/views/stores.php" class="btn btn-dark">
                    Find a Store Near You
                </a>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/../app/views/partials/footer.php'; ?>
