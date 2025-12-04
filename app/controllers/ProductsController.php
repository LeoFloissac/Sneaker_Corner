<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/ProductVariant.php';
require_once __DIR__ . '/../models/Color.php';
require_once __DIR__ . '/../models/VariantImage.php';
require_once __DIR__ . '/../models/VariantSize.php';
require_once __DIR__ . '/../models/StoreInventory.php';

class ProductsController {
    private $productModel;
    private $variantModel;
    private $colorModel;
    private $imageModel;
    private $sizeModel;
    private $inventoryModel;

    public function __construct($mysqliConn = null) {
        $this->productModel = new Product($mysqliConn);
        $this->variantModel = new ProductVariant($mysqliConn);
        $this->colorModel = new Color($mysqliConn);
        $this->imageModel = new VariantImage($mysqliConn);
        $this->sizeModel = new VariantSize($mysqliConn);
        $this->inventoryModel = new StoreInventory($mysqliConn);
    }

    /**
     * Get all available brands for filtering
     */
    public function getBrands() {
        return $this->productModel->findAllBrands();
    }

    /**
     * Get all available colors for filtering
     */
    public function getColors() {
        return $this->colorModel->findAllAvailable();
    }

    /**
     * Get all available sizes for filtering
     */
    public function getSizes() {
        return $this->sizeModel->findAllDistinctAvailable();
    }

    /**
     * Get active filters info for breadcrumb display
     */
    public function getActiveFilters($filters) {
        $activeFilters = [];
        
        if (!empty($filters['brand'])) {
            $activeFilters['brand'] = [
                'type' => 'Brand',
                'value' => $filters['brand'],
                'param' => 'brand'
            ];
        }
        
        if (!empty($filters['color'])) {
            $colorName = $this->colorModel->findNameBySlug($filters['color']);
            $activeFilters['color'] = [
                'type' => 'Color',
                'value' => $colorName ?: $filters['color'],
                'param' => 'color'
            ];
        }
        
        if (!empty($filters['size'])) {
            $activeFilters['size'] = [
                'type' => 'Size',
                'value' => $filters['size'],
                'param' => 'size'
            ];
        }
        
        return $activeFilters;
    }

    /**
     * Search products by name, brand, or model
     * Returns limited results for AJAX suggestions
     */
    public function search($query, $limit = 8) {
        $rows = $this->variantModel->search($query, $limit);
        return $this->formatVariantsForList($rows);
    }

    /**
     * Get all product variants with optional filters
     */
    public function index($filters = []) {
        $rows = $this->variantModel->findAllWithFilters($filters);
        return $this->formatVariantsForGrid($rows);
    }

    /**
     * Get details for a specific variant
     * Accepts either variant_id or product_slug + color_slug
     */
    public function show($variantId = null, $productSlug = null, $colorSlug = null) {
        // Find variant ID from slugs if needed
        if ($productSlug && $colorSlug) {
            $variantId = $this->variantModel->findIdBySlugs($productSlug, $colorSlug);
        }
        
        if (!$variantId) {
            return null;
        }
        
        $variantId = (int)$variantId;
        
        // Get variant details
        $variant = $this->variantModel->findById($variantId);
        if (!$variant) {
            return null;
        }

        // Get related data from individual models
        $images = $this->imageModel->findByVariantId($variantId);
        $sizes = $this->sizeModel->findByVariantId($variantId);
        $colors = $this->variantModel->findColorVariants($variant['product_id']);
        $stores = $this->inventoryModel->findStoresWithStock($variantId);

        // Format data for view
        return $this->formatVariantDetail($variant, $images, $sizes, $colors, $stores, $variantId);
    }

    /**
     * Format variants for product grid display
     */
    private function formatVariantsForGrid($rows) {
        $products = [];

        foreach ($rows as $r) {
            $products[] = [
                'variant_id' => $r['variant_id'],
                'product_id' => $r['product_id'],
                'product_slug' => $r['product_slug'],
                'color_slug' => $r['color_slug'],
                'title' => $this->getVariantTitle($r),
                'subtitle' => $this->getVariantSubtitle($r),
                'price' => $this->formatPrice($r['price']),
                'img' => $this->formatImageUrl($r['image_url']),
            ];
        }

        return $products;
    }

    /**
     * Format variants for search results list
     */
    private function formatVariantsForList($rows) {
        $products = [];

        foreach ($rows as $r) {
            $subtitleParts = [];
            if (!empty($r['brand'])) $subtitleParts[] = $r['brand'];
            if (!empty($r['color_name'])) $subtitleParts[] = $r['color_name'];

            $products[] = [
                'variant_id' => $r['variant_id'],
                'title' => $this->getVariantTitle($r),
                'subtitle' => implode(' - ', $subtitleParts),
                'price' => $this->formatPrice($r['price']),
                'img' => $this->formatImageUrl($r['image_url']),
                'url' => '/sneaker_corner/app/views/product-detail.php?slug=' . $r['product_slug'] . '&color=' . $r['color_slug']
            ];
        }

        return $products;
    }

    /**
     * Format variant detail for product page
     */
    private function formatVariantDetail($variant, $images, $sizes, $colors, $stores, $variantId) {
        // Format images
        $formattedImages = [];
        foreach ($images as $img) {
            $formattedImages[] = [
                'id' => $img['image_id'],
                'url' => $this->formatImageUrl($img['image_url']),
                'alt' => $img['alt_text'] ?? $variant['variant_name'],
                'is_primary' => $img['is_primary']
            ];
        }

        // Default image if none exists
        if (empty($formattedImages)) {
            $formattedImages[] = [
                'id' => 0,
                'url' => '/sneaker_corner/images/placeholder.png',
                'alt' => $variant['variant_name'],
                'is_primary' => 1
            ];
        }

        // Format color options
        $formattedColors = [];
        foreach ($colors as $col) {
            $formattedColors[] = [
                'variant_id' => $col['variant_id'],
                'name' => $col['color_name'],
                'slug' => $col['color_slug'],
                'image' => $this->formatImageUrl($col['image_url']),
                'is_current' => ($col['variant_id'] == $variantId)
            ];
        }

        // Format stores with maps URLs
        $formattedStores = $this->formatStoresWithMaps($stores);

        return [
            'variant_id' => $variant['variant_id'],
            'product_id' => $variant['product_id'],
            'product_slug' => $variant['slug'],
            'color_slug' => $variant['color_slug'],
            'name' => $variant['variant_name'] ?: $variant['product_name'],
            'product_name' => $variant['product_name'],
            'brand' => $variant['brand'],
            'model' => $variant['model'],
            'color' => $variant['color_name'],
            'price' => $this->formatPrice($variant['price']),
            'price_raw' => (float)$variant['price'],
            'images' => $formattedImages,
            'sizes' => $sizes,
            'colors' => $formattedColors,
            'stores' => $formattedStores
        ];
    }

    /**
     * Get variant display title
     */
    private function getVariantTitle($row) {
        return !empty($row['variant_name']) ? $row['variant_name'] : ($row['product_name'] ?? '');
    }

    /**
     * Get variant subtitle (brand - model - color)
     */
    private function getVariantSubtitle($row) {
        $parts = [];
        if (!empty($row['brand'])) $parts[] = $row['brand'];
        if (!empty($row['model'])) $parts[] = $row['model'];
        if (!empty($row['color_name'])) $parts[] = $row['color_name'];
        return implode(' - ', $parts);
    }

    /**
     * Format price for display
     */
    private function formatPrice($price) {
        return ($price !== null) ? number_format((float)$price, 2) . ' €' : 'N/A';
    }

    /**
     * Format image URL with base path
     */
    private function formatImageUrl($imageUrl) {
        if (empty($imageUrl)) {
            return '/sneaker_corner/images/placeholder.png';
        }
        return '/sneaker_corner/' . ltrim($imageUrl, '/');
    }

    /**
     * Add Google Maps URLs to stores
     */
    private function formatStoresWithMaps($stores) {
        foreach ($stores as &$store) {
            if ($store['latitude'] && $store['longitude']) {
                $store['maps_url'] = "https://www.google.com/maps?q={$store['latitude']},{$store['longitude']}";
            } else {
                $address = urlencode("{$store['address']}, {$store['postal_code']} {$store['city']}");
                $store['maps_url'] = "https://www.google.com/maps/search/?api=1&query={$address}";
            }
        }
        return $stores;
    }
}
