<?php

class Product {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM products ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findBySlug($slug) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByBrand($brand) {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE brand = ? ORDER BY created_at DESC");
        $stmt->execute([$brand]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findAvailable() {
        $stmt = $this->pdo->query("SELECT * FROM products WHERE is_available = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (name, slug, brand, model, release_date, is_available, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['brand'],
            $data['model'],
            $data['release_date'] ?? null,
            $data['is_available'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE products 
            SET name = ?, slug = ?, brand = ?, model = ?, release_date = ?, is_available = ?, updated_at = NOW()
            WHERE product_id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['brand'],
            $data['model'],
            $data['release_date'] ?? null,
            $data['is_available'] ?? 1,
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE product_id = ?");
        return $stmt->execute([$id]);
    }
}