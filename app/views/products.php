<?php 
    // Initialize controller - config.php is already loaded by auth_handler.php via header.php
    require_once __DIR__ . '/../../config/config.php';
    require_once __DIR__ . '/../controllers/ProductsController.php';

    $extraCSS = ["/sneaker_corner/css/products.css","/sneaker_corner/css/product.css"];
    include 'partials/header.php'; 

    $productsController = new ProductsController($conn ?? null);

    // Get filter values from URL
    $filters = [
        'brand' => $_GET['brand'] ?? null,
        'color' => $_GET['color'] ?? null,
        'size' => $_GET['size'] ?? null
    ];

    // Get filter options
    $brands = $productsController->getBrands();
    $colors = $productsController->getColors();
    $sizes = $productsController->getSizes();

    // Get active filters for breadcrumb
    $activeFilters = $productsController->getActiveFilters($filters);

    // Get products with filters
    $products = $productsController->index($filters);
    
    // Shuffle products for random order display
    shuffle($products);

    // Lazy loading configuration
    $productsPerPage = 12;
    $totalProducts = count($products);
    $initialProducts = array_slice($products, 0, $productsPerPage);
    $remainingProducts = array_slice($products, $productsPerPage);

    // Helper function to build URL with updated filters
    function buildFilterUrl($newParams = [], $removeParam = null) {
        $currentParams = $_GET;
        if ($removeParam) {
            unset($currentParams[$removeParam]);
        }
        $params = array_merge($currentParams, $newParams);
        $params = array_filter($params); // Remove empty values
        $queryString = http_build_query($params);
        return '/sneaker_corner/app/views/products.php' . ($queryString ? '?' . $queryString : '');
    }
?>

<main class="products-page">
    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <ol class="breadcrumb-list">
            <li class="breadcrumb-item">
                <a href="/sneaker_corner/public/index.php">Home</a>
            </li>
            <li class="breadcrumb-item">
                <?php if (empty($activeFilters)): ?>
                    <span class="breadcrumb-current">Products</span>
                <?php else: ?>
                    <a href="/sneaker_corner/app/views/products.php">Products</a>
                <?php endif; ?>
            </li>
            <?php foreach ($activeFilters as $key => $filter): ?>
                <li class="breadcrumb-item">
                    <span class="breadcrumb-current"><?php echo htmlspecialchars($filter['type'] . ': ' . $filter['value'], ENT_QUOTES, 'UTF-8'); ?></span>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>

    <div class="products-container">
        <!-- Filters Sidebar -->
        <aside class="filters-sidebar">
            <div class="filters-header">
                <h2>Filters</h2>
                <?php if (!empty($activeFilters)): ?>
                    <a href="/sneaker_corner/app/views/products.php" class="clear-all-filters">Clear All</a>
                <?php endif; ?>
            </div>

            <!-- Active Filters Tags -->
            <?php if (!empty($activeFilters)): ?>
                <div class="active-filters">
                    <?php foreach ($activeFilters as $key => $filter): ?>
                        <span class="filter-tag">
                            <?php echo htmlspecialchars($filter['value'], ENT_QUOTES, 'UTF-8'); ?>
                            <a href="<?php echo buildFilterUrl([], $filter['param']); ?>" class="remove-filter" aria-label="Remove <?php echo htmlspecialchars($filter['value'], ENT_QUOTES, 'UTF-8'); ?> filter">&times;</a>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Brand Filter -->
            <div class="filter-group">
                <h3 class="filter-title" data-toggle="brand-options">
                    Brand
                    <span class="toggle-icon">▼</span>
                </h3>
                <ul class="filter-options" id="brand-options">
                    <?php foreach ($brands as $brand): ?>
                        <li>
                            <a href="<?php echo buildFilterUrl(['brand' => $brand]); ?>" 
                               class="filter-option <?php echo ($filters['brand'] === $brand) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Color Filter -->
            <div class="filter-group">
                <h3 class="filter-title" data-toggle="color-options">
                    Color
                    <span class="toggle-icon">▼</span>
                </h3>
                <ul class="filter-options" id="color-options">
                    <?php foreach ($colors as $color): ?>
                        <li>
                            <a href="<?php echo buildFilterUrl(['color' => $color['slug']]); ?>" 
                               class="filter-option <?php echo ($filters['color'] === $color['slug']) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($color['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Size Filter -->
            <div class="filter-group">
                <h3 class="filter-title" data-toggle="size-options">
                    Size
                    <span class="toggle-icon">▼</span>
                </h3>
                <ul class="filter-options size-grid" id="size-options">
                    <?php foreach ($sizes as $size): ?>
                        <li>
                            <a href="<?php echo buildFilterUrl(['size' => $size]); ?>" 
                               class="filter-option size-option <?php echo ($filters['size'] === $size) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($size, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </aside>

        <!-- Products Content -->
        <div class="products-content">
            <div class="products-header">
                <h1>Products</h1>
                <span class="result-count"><?php echo count($products); ?> result<?php echo count($products) !== 1 ? 's' : ''; ?></span>
            </div>

            <!-- Search Bar with AJAX -->
            <div class="search-container">
                <input 
                    type="text" 
                    id="product-search" 
                    class="search-input" 
                    placeholder="🔍 Search products..."
                    autocomplete="off"
                >
                <div id="search-results" class="search-results"></div>
            </div>

            <div id="product-selection">
                <?php 
                    if (!empty($initialProducts)) {
                        foreach ($initialProducts as $prod) {
                            // valeurs que le partial attend
                            $variantId = $prod['variant_id'];
                            $productSlug = $prod['product_slug'];
                            $colorSlug = $prod['color_slug'];
                            $title = $prod['title'];
                            $subtitle = $prod['subtitle'];
                            $price = $prod['price'];
                            $img = $prod['img'];

                            include 'partials/product.php';
                        }
                    } else {
                        echo '<div class="empty-state"><p>No products found matching your criteria.</p><a href="/sneaker_corner/app/views/products.php" class="btn-clear-filters">Clear all filters</a></div>';
                    }
                ?>
            </div>

            <!-- Lazy Loading: Hidden products data -->
            <?php if (!empty($remainingProducts)): ?>
            <div id="lazy-load-trigger"></div>
            <div id="lazy-load-spinner" class="lazy-load-spinner">
                <div class="spinner"></div>
                <span>Loading more products...</span>
            </div>
            <script id="remaining-products-data" type="application/json">
                <?php echo json_encode($remainingProducts); ?>
            </script>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Toggle filter groups
document.querySelectorAll('.filter-title').forEach(title => {
    title.addEventListener('click', function() {
        const targetId = this.getAttribute('data-toggle');
        const options = document.getElementById(targetId);
        const icon = this.querySelector('.toggle-icon');
        
        if (options) {
            options.classList.toggle('collapsed');
            icon.textContent = options.classList.contains('collapsed') ? '▶' : '▼';
        }
    });
});

// AJAX Search functionality
(function() {
    const searchInput = document.getElementById('product-search');
    const searchResults = document.getElementById('search-results');
    let searchTimeout = null;
    let currentRequest = null;

    // Debounced search function
    function performSearch(query) {
        if (query.length < 2) {
            hideResults();
            return;
        }

        // Cancel previous request if exists
        if (currentRequest) {
            currentRequest.abort();
        }

        // Show loading state
        searchResults.innerHTML = '<div class="search-loading">Searching...</div>';
        searchResults.classList.add('active');

        // Create new request
        currentRequest = new XMLHttpRequest();
        currentRequest.open('GET', '/sneaker_corner/app/api/search.php?q=' + encodeURIComponent(query), true);
        
        currentRequest.onload = function() {
            if (currentRequest.status === 200) {
                try {
                    const data = JSON.parse(currentRequest.responseText);
                    displayResults(data.results, query);
                } catch (e) {
                    searchResults.innerHTML = '<div class="search-error">Error parsing results</div>';
                }
            } else {
                searchResults.innerHTML = '<div class="search-error">Error loading results</div>';
            }
        };

        currentRequest.onerror = function() {
            searchResults.innerHTML = '<div class="search-error">Network error</div>';
        };

        currentRequest.send();
    }

    // Display search results
    function displayResults(results, query) {
        if (results.length === 0) {
            searchResults.innerHTML = '<div class="search-no-results">No products found for "' + escapeHtml(query) + '"</div>';
            return;
        }

        let html = '<ul class="search-results-list">';
        results.forEach(function(product) {
            html += `
                <li class="search-result-item">
                    <a href="${product.url}" class="search-result-link">
                        <img src="${product.img}" alt="${escapeHtml(product.title)}" class="search-result-img" onerror="this.src='/sneaker_corner/images/placeholder.png'">
                        <div class="search-result-info">
                            <span class="search-result-title">${highlightMatch(product.title, query)}</span>
                            <span class="search-result-subtitle">${highlightMatch(product.subtitle, query)}</span>
                            <span class="search-result-price">${product.price}</span>
                        </div>
                    </a>
                </li>
            `;
        });
        html += '</ul>';
        
        if (results.length >= 8) {
            html += '<div class="search-view-all">Showing top ' + results.length + ' results</div>';
        }

        searchResults.innerHTML = html;
        searchResults.classList.add('active');
    }

    // Hide results dropdown
    function hideResults() {
        searchResults.classList.remove('active');
        searchResults.innerHTML = '';
    }

    // Highlight matching text
    function highlightMatch(text, query) {
        if (!text) return '';
        const regex = new RegExp('(' + escapeRegex(query) + ')', 'gi');
        return escapeHtml(text).replace(regex, '<mark>$1</mark>');
    }

    // Escape HTML special characters
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Escape regex special characters
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Event: Input change with debounce
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Debounce: wait 300ms before searching
        searchTimeout = setTimeout(function() {
            performSearch(query);
        }, 300);
    });

    // Event: Click outside to close results
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            hideResults();
        }
    });

    // Event: Focus on input shows results if query exists
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            performSearch(this.value.trim());
        }
    });

    // Event: Escape key closes results
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideResults();
            this.blur();
        }
    });
})();

// Lazy Loading functionality
(function() {
    const productContainer = document.getElementById('product-selection');
    const lazyTrigger = document.getElementById('lazy-load-trigger');
    const lazySpinner = document.getElementById('lazy-load-spinner');
    const dataScript = document.getElementById('remaining-products-data');
    
    if (!dataScript || !lazyTrigger) return;
    
    let remainingProducts = [];
    try {
        remainingProducts = JSON.parse(dataScript.textContent);
    } catch (e) {
        console.error('Error parsing products data:', e);
        return;
    }
    
    const PRODUCTS_PER_LOAD = 12;
    let isLoading = false;
    
    // Create product card HTML (matching partials/product.php structure)
    function createProductCard(product) {
        const card = document.createElement('a');
        card.href = '/sneaker_corner/app/views/product-detail.php?slug=' + encodeURIComponent(product.product_slug) + '&color=' + encodeURIComponent(product.color_slug);
        card.className = 'product fade-in';
        
        let subtitleHtml = '';
        if (product.subtitle) {
            subtitleHtml = `<p class="product-subtitle">${escapeHtml(product.subtitle)}</p>`;
        }
        
        card.innerHTML = `
            <div class="image-frame">
                <img src="${escapeHtml(product.img)}" alt="${escapeHtml(product.title)}" class="product-image" onerror="this.src='/sneaker_corner/images/placeholder.png'">
            </div>
            <p class="product-title-price">${escapeHtml(product.title)}</p>
            ${subtitleHtml}
            <p class="product-title-price">${escapeHtml(product.price)}</p>
        `;
        
        return card;
    }
    
    // Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Load more products
    function loadMoreProducts() {
        if (isLoading || remainingProducts.length === 0) return;
        
        isLoading = true;
        lazySpinner.classList.add('visible');
        
        // Simulate network delay for smooth UX
        setTimeout(function() {
            const productsToLoad = remainingProducts.splice(0, PRODUCTS_PER_LOAD);
            
            productsToLoad.forEach(function(product) {
                const card = createProductCard(product);
                productContainer.appendChild(card);
                
                // Trigger animation
                requestAnimationFrame(function() {
                    card.classList.add('visible');
                });
            });
            
            isLoading = false;
            
            // Hide spinner and trigger if no more products
            if (remainingProducts.length === 0) {
                lazySpinner.classList.remove('visible');
                lazySpinner.style.display = 'none';
                lazyTrigger.style.display = 'none';
            } else {
                lazySpinner.classList.remove('visible');
            }
        }, 300);
    }
    
    // Intersection Observer for lazy loading
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting && !isLoading && remainingProducts.length > 0) {
                loadMoreProducts();
            }
        });
    }, {
        root: null,
        rootMargin: '200px', // Load before reaching the trigger
        threshold: 0
    });
    
    observer.observe(lazyTrigger);
})();
</script>

<?php include 'partials/footer.php'; ?>
