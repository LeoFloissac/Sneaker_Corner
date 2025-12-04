<?php

class VariantImage {
    private $pdo;
    private $mysqliConn;
    
    public function __construct($mysqliConn = null) {
        $this->mysqliConn = $mysqliConn;

        if (file_exists(__DIR__ . '/../../config/config.php')) {
            require_once __DIR__ . '/../../config/config.php';
        }

        try {
            if (isset($servername) && isset($name)) {
                $dsn = "mysql:host={$servername};dbname={$dbname};charset=utf8mb4";
                $this->pdo = new PDO($dsn, $name, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            }
        } catch (Exception $e) {
            $this->pdo = null;
        }
    }

    /**
     * Execute a query and return results
     */
    private function executeQuery($sql, $params = []) {
        $rows = [];
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($this->mysqliConn) {
            if (empty($params)) {
                $res = $this->mysqliConn->query($sql);
            } else {
                $stmt = $this->mysqliConn->prepare($sql);
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $res = $stmt->get_result();
            }
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $rows[] = $r;
                }
                if (method_exists($res, 'free')) $res->free();
            }
        }
        
        return $rows;
    }

    /**
     * Execute a query and return single result
     */
    private function executeQuerySingle($sql, $params = []) {
        $rows = $this->executeQuery($sql, $params);
        return !empty($rows) ? $rows[0] : null;
    }
    
    /**
     * Find all images
     */
    public function findAll() {
        $sql = "SELECT * FROM variant_images ORDER BY display_order ASC";
        return $this->executeQuery($sql);
    }
    
    /**
     * Find image by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM variant_images WHERE image_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }
    
    /**
     * Find images by variant ID
     */
    public function findByVariantId($variantId) {
        $sql = "SELECT image_id, image_url, alt_text, is_primary, display_order
                FROM variant_images 
                WHERE variant_id = ? 
                ORDER BY is_primary DESC, display_order ASC";
        return $this->executeQuery($sql, [$variantId]);
    }
    
    /**
     * Find primary image for a variant
     */
    public function findPrimaryImage($variantId) {
        $sql = "SELECT * FROM variant_images 
                WHERE variant_id = ? AND is_primary = 1
                LIMIT 1";
        return $this->executeQuerySingle($sql, [$variantId]);
    }
    
    /**
     * Create a new image
     */
    public function create($data) {
        $sql = "INSERT INTO variant_images (variant_id, image_url, alt_text, is_primary, display_order) 
                VALUES (?, ?, ?, ?, ?)";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $data['variant_id'],
                $data['image_url'],
                $data['alt_text'] ?? '',
                $data['is_primary'] ?? 0,
                $data['display_order'] ?? 0
            ]);
            return $this->pdo->lastInsertId();
        }
        return null;
    }
    
    /**
     * Update an image
     */
    public function update($id, $data) {
        $sql = "UPDATE variant_images 
                SET variant_id = ?, image_url = ?, alt_text = ?, is_primary = ?, display_order = ?
                WHERE image_id = ?";
        
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $data['variant_id'],
                $data['image_url'],
                $data['alt_text'] ?? '',
                $data['is_primary'] ?? 0,
                $data['display_order'] ?? 0,
                $id
            ]);
        }
        return false;
    }
    
    /**
     * Set an image as primary
     */
    public function setPrimary($imageId, $variantId) {
        if ($this->pdo) {
            // First, remove primary status from all images of this variant
            $stmt = $this->pdo->prepare("UPDATE variant_images SET is_primary = 0 WHERE variant_id = ?");
            $stmt->execute([$variantId]);
            
            // Then, set the new primary image
            $stmt = $this->pdo->prepare("UPDATE variant_images SET is_primary = 1 WHERE image_id = ?");
            return $stmt->execute([$imageId]);
        }
        return false;
    }
    
    /**
     * Delete an image
     */
    public function delete($id) {
        $sql = "DELETE FROM variant_images WHERE image_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        }
        return false;
    }
}