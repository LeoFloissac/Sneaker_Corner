<?php

class ProductVariant {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("
            SELECT pv.*, p.name as product_name, p.brand, c.name as color_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.product_id
            JOIN colors c ON pv.color_id = c.color_id
            ORDER BY pv.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("
            SELECT pv.*, p.name as product_name, p.brand, p.model, c.name as color_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.product_id
            JOIN colors c ON pv.color_id = c.color_id
            WHERE pv.variant_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByProductId($productId) {
        $stmt = $this->pdo->prepare("
            SELECT pv.*, c.name as color_name, c.slug as color_slug
            FROM product_variants pv
            JOIN colors c ON pv.color_id = c.color_id
            WHERE pv.product_id = ?
            ORDER BY pv.variant_name ASC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findAvailable() {
        $stmt = $this->pdo->query("
            SELECT pv.*, p.name as product_name, p.brand, c.name as color_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.product_id
            JOIN colors c ON pv.color_id = c.color_id
            WHERE pv.is_available = 1 AND p.is_available = 1
            ORDER BY pv.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO product_variants (product_id, color_id, variant_name, price, is_available, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $data['product_id'],
            $data['color_id'],
            $data['variant_name'],
            $data['price'],
            $data['is_available'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE product_variants 
            SET product_id = ?, color_id = ?, variant_name = ?, price = ?, is_available = ?
            WHERE variant_id = ?
        ");
        return $stmt->execute([
            $data['product_id'],
            $data['color_id'],
            $data['variant_name'],
            $data['price'],
            $data['is_available'] ?? 1,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM product_variants WHERE variant_id = ?");
        return $stmt->execute([$id]);
    }
}