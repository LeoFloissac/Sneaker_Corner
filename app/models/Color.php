<?php

class Color {
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
     * Find all colors
     */
    public function findAll() {
        $sql = "SELECT * FROM colors ORDER BY name ASC";
        return $this->executeQuery($sql);
    }
    
    /**
     * Find color by ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM colors WHERE color_id = ?";
        return $this->executeQuerySingle($sql, [$id]);
    }
    
    /**
     * Find color by slug
     */
    public function findBySlug($slug) {
        $sql = "SELECT * FROM colors WHERE slug = ?";
        return $this->executeQuerySingle($sql, [$slug]);
    }

    /**
     * Get color name by slug
     */
    public function findNameBySlug($slug) {
        $result = $this->findBySlug($slug);
        return $result ? $result['name'] : null;
    }

    /**
     * Find all available colors (with active variants)
     */
    public function findAllAvailable() {
        $sql = "SELECT DISTINCT c.color_id, c.name, c.slug
                FROM colors c 
                JOIN product_variants pv ON c.color_id = pv.color_id
                JOIN products p ON pv.product_id = p.product_id
                WHERE pv.is_available = 1 AND p.is_available = 1
                ORDER BY c.name ASC";
        
        return $this->executeQuery($sql);
    }
    
    /**
     * Create a new color
     */
    public function create($data) {
        $sql = "INSERT INTO colors (name, slug) VALUES (?, ?)";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$data['name'], $data['slug']]);
            return $this->pdo->lastInsertId();
        }
        return null;
    }
    
    /**
     * Update a color
     */
    public function update($id, $data) {
        $sql = "UPDATE colors SET name = ?, slug = ? WHERE color_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$data['name'], $data['slug'], $id]);
        }
        return false;
    }
    
    /**
     * Delete a color
     */
    public function delete($id) {
        $sql = "DELETE FROM colors WHERE color_id = ?";
        if ($this->pdo) {
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        }
        return false;
    }
}