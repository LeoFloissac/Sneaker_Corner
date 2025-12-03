-- Create DB
CREATE DATABASE IF NOT EXISTS sneaker_corner CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE sneaker_corner;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: products
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    brand VARCHAR(100) NOT NULL,
    model VARCHAR(255),
    release_date DATE NULL,
    is_available TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_brand (brand),
    INDEX idx_slug (slug),
    INDEX idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: colors
CREATE TABLE IF NOT EXISTS colors (
    color_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: product_variants
CREATE TABLE IF NOT EXISTS product_variants (
    variant_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    color_id INT NOT NULL,
    variant_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    is_available TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (color_id) REFERENCES colors(color_id) ON DELETE RESTRICT,
    INDEX idx_product (product_id),
    INDEX idx_color (color_id),
    INDEX idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: variant_sizes
CREATE TABLE IF NOT EXISTS variant_sizes (
    size_id INT AUTO_INCREMENT PRIMARY KEY,
    variant_id INT NOT NULL,
    size VARCHAR(20) NOT NULL,
    stock_quantity INT DEFAULT 0,
    FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id) ON DELETE CASCADE,
    INDEX idx_variant (variant_id),
    UNIQUE KEY unique_variant_size (variant_id, size)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: variant_images
CREATE TABLE IF NOT EXISTS variant_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    variant_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255),
    is_primary TINYINT(1) DEFAULT 0,
    display_order INT DEFAULT 0,
    FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id) ON DELETE CASCADE,
    INDEX idx_variant (variant_id),
    INDEX idx_primary (is_primary)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: store_inventory
CREATE TABLE IF NOT EXISTS store_inventory (
    inventory_id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT NOT NULL,
    variant_id INT NOT NULL,
    size_id INT NOT NULL,
    quantity INT DEFAULT 0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (variant_id) REFERENCES product_variants(variant_id) ON DELETE CASCADE,
    FOREIGN KEY (size_id) REFERENCES variant_sizes(size_id) ON DELETE CASCADE,
    INDEX idx_store (store_id),
    INDEX idx_variant (variant_id),
    UNIQUE KEY unique_store_variant_size (store_id, variant_id, size_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
