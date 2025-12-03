<?php

class VariantImage {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM variant_images ORDER BY display_order ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM variant_images WHERE image_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByVariantId($variantId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM variant_images 
            WHERE variant_id = ? 
            ORDER BY display_order ASC
        ");
        $stmt->execute([$variantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findPrimaryImage($variantId) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM variant_images 
            WHERE variant_id = ? AND is_primary = 1
            LIMIT 1
        ");
        $stmt->execute([$variantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO variant_images (variant_id, image_url, alt_text, is_primary, display_order) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['variant_id'],
            $data['image_url'],
            $data['alt_text'] ?? '',
            $data['is_primary'] ?? 0,
            $data['display_order'] ?? 0
        ]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("
            UPDATE variant_images 
            SET variant_id = ?, image_url = ?, alt_text = ?, is_primary = ?, display_order = ?
            WHERE image_id = ?
        ");
        return $stmt->execute([
            $data['variant_id'],
            $data['image_url'],
            $data['alt_text'] ?? '',
            $data['is_primary'] ?? 0,
            $data['display_order'] ?? 0,
            $id
        ]);
    }
    
    public function setPrimary($imageId, $variantId) {
        // D'abord, retirer le statut primary de toutes les images de cette variante
        $stmt = $this->pdo->prepare("UPDATE variant_images SET is_primary = 0 WHERE variant_id = ?");
        $stmt->execute([$variantId]);
        
        // Ensuite, définir la nouvelle image principale
        $stmt = $this->pdo->prepare("UPDATE variant_images SET is_primary = 1 WHERE image_id = ?");
        return $stmt->execute([$imageId]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM variant_images WHERE image_id = ?");
        return $stmt->execute([$id]);
    }
}