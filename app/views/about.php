<?php 
    $extraCSS = ["/sneaker_corner/css/about.css"];
    include 'partials/header.php'; 
?>

<main class="page-main about-page">
    <!-- Hero Section -->
    <section class="page-hero hero-large">
        <h1>About Sneaker Corner</h1>
        <p>Your destination for authentic sneakers in Vietnam. We bring the world's best footwear brands to sneaker enthusiasts across the country.</p>
    </section>

    <!-- Our Story Section -->
    <section class="about-story">
        <div class="page-container narrow">
            <div class="story-content">
                <div class="story-text">
                    <h2>Our Story</h2>
                    <p>Founded in 2020, Sneaker Corner began with a simple mission: to bring authentic, high-quality sneakers to Vietnam. What started as a small passion project has grown into one of the country's most trusted sneaker destinations.</p>
                    <p>We believe that sneakers are more than just footwear—they're a form of self-expression, a piece of culture, and a connection to communities around the world. That's why we carefully curate our collection to include the most sought-after releases and timeless classics.</p>
                    <p>Today, with stores in Hanoi, Ho Chi Minh City, and Da Nang, we continue to serve the growing sneaker community in Vietnam with the same passion and dedication that inspired us from day one.</p>
                </div>
                <div class="story-image">
                    <div class="story-image-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="about-values">
        <div class="page-container narrow">
            <h2>Our Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3>Authenticity</h3>
                    <p>Every sneaker we sell is 100% authentic. We work directly with official distributors and brands to guarantee genuine products.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3>Passion</h3>
                    <p>We're sneakerheads ourselves. Our team lives and breathes sneaker culture, bringing genuine enthusiasm to every customer interaction.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3>Community</h3>
                    <p>We're building more than a store—we're fostering a community of sneaker lovers who share our passion for great footwear.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="page-section bg-dark about-stats">
        <div class="page-container narrow">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Stores in Vietnam</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-label">Premium Brands</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">5</div>
                    <div class="stat-label">Years of Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta">
        <div class="page-container narrow">
            <h2>Ready to Find Your Perfect Pair?</h2>
            <p>Browse our collection online and visit one of our stores to try them on.</p>
            <div class="cta-buttons">
                <a href="/sneaker_corner/app/views/products.php" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Shop Now
                </a>
                <a href="/sneaker_corner/app/views/stores.php" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Find a Store
                </a>
            </div>
        </div>
    </section>
</main>

<?php include 'partials/footer.php'; ?>
