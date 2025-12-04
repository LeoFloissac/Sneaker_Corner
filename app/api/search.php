<?php
/**
 * AJAX Search API Endpoint
 * Returns product suggestions based on search query
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Load configuration and controller
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../controllers/ProductsController.php';

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Minimum 2 characters required
if (strlen($query) < 2) {
    echo json_encode(['results' => [], 'count' => 0]);
    exit;
}

// Initialize controller
$productsController = new ProductsController($conn ?? null);

// Search products
$results = $productsController->search($query);

echo json_encode([
    'results' => $results,
    'count' => count($results),
    'query' => $query
]);
