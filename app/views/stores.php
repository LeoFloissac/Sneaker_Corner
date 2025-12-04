<?php 
    // Initialize controller - config.php is already loaded by auth_handler.php via header.php
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../controllers/StoresController.php';

    $extraCSS = ["/sneaker_corner/css/stores.css"];
    include 'partials/header.php'; 

    $storesController = new StoresController($conn ?? null);
    $stores = $storesController->index();
?>

<main class="page-main stores-page">
    <!-- Hero Section -->
    <section class="page-hero">
        <h1>Our Stores</h1>
        <p>Visit us in-store to try on your favorite sneakers and get expert advice from our team.</p>
    </section>

    <div class="page-container">
        <?php if (!empty($stores)): ?>
            <!-- Stores Grid -->
            <div class="stores-grid">
                <?php foreach ($stores as $store): ?>
                    <article class="card store-card">
                        <!-- Store Map -->
                        <div class="store-map">
                            <?php if ($store['latitude'] && $store['longitude']): ?>
                                <iframe 
                                    src="https://www.google.com/maps?q=<?php echo $store['latitude']; ?>,<?php echo $store['longitude']; ?>&output=embed"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Map of <?php echo htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8'); ?>">
                                </iframe>
                            <?php else: ?>
                                <div class="store-map-placeholder">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Store Info -->
                        <div class="store-info">
                            <h2 class="store-name"><?php echo htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                            
                            <ul class="icon-list store-details">
                                <!-- Address -->
                                <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span><?php echo htmlspecialchars($store['full_address'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </li>

                                <!-- Phone -->
                                <?php if (!empty($store['phone'])): ?>
                                    <li>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $store['phone']), ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($store['phone'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Email -->
                                <?php if (!empty($store['email'])): ?>
                                    <li>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a href="mailto:<?php echo htmlspecialchars($store['email'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($store['email'], ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <!-- Opening Hours -->
                                <?php if (!empty($store['opening_hours'])): ?>
                                    <li>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span><?php echo htmlspecialchars($store['opening_hours'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>

                            <!-- Actions -->
                            <div class="store-actions">
                                <a href="<?php echo htmlspecialchars($store['maps_url'], ENT_QUOTES, 'UTF-8'); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="btn btn-primary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    Get Directions
                                </a>
                                <?php if (!empty($store['phone'])): ?>
                                    <a href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $store['phone']), ENT_QUOTES, 'UTF-8'); ?>" 
                                       class="btn btn-secondary btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        Call Store
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Info Section -->
            <section class="stores-info-section">
                <h2>Why Visit Us In-Store?</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <h3>Authentic Products</h3>
                        <p>100% genuine sneakers from official brands with warranty.</p>
                    </div>
                    <div class="info-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3>Expert Staff</h3>
                        <p>Our trained team helps you find the perfect fit and style.</p>
                    </div>
                    <div class="info-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3>In-Store Purchase Only</h3>
                        <p>Browse online, then visit us to try on and buy your sneakers.</p>
                    </div>
                    <div class="info-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <h3>Check Availability</h3>
                        <p>See stock levels online before visiting to ensure your size is available.</p>
                    </div>
                </div>
            </section>

        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h2>No Stores Available</h2>
                <p>We're working on opening new locations. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'partials/footer.php'; ?>
