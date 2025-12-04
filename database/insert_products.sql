USE sneaker_corner;

/* ---------------------------------------------
   INSERT CATEGORIES
--------------------------------------------- */
INSERT INTO categories (name, slug, parent_id, description) VALUES
('Chaussures', 'chaussures', NULL, 'Toutes les chaussures'),
('Running', 'running', 1, 'Chaussures de course'),
('Lifestyle', 'lifestyle', 1, 'Chaussures lifestyle'),
('Skateboard', 'skateboard', 1, 'Chaussures de skate'),
('Basketball', 'basketball', 1, 'Chaussures de basketball'),
('Homme', 'homme', NULL, 'Collection homme'),
('Femme', 'femme', NULL, 'Collection femme');

/* Récupération des IDs catégories */
SET @cat_chaussures := (SELECT category_id FROM categories WHERE slug='chaussures');
SET @cat_running := (SELECT category_id FROM categories WHERE slug='running');
SET @cat_lifestyle := (SELECT category_id FROM categories WHERE slug='lifestyle');
SET @cat_skateboard := (SELECT category_id FROM categories WHERE slug='skateboard');
SET @cat_basketball := (SELECT category_id FROM categories WHERE slug='basketball');
SET @cat_homme := (SELECT category_id FROM categories WHERE slug='homme');
SET @cat_femme := (SELECT category_id FROM categories WHERE slug='femme');

/* ---------------------------------------------
   INSERT STORES (Vietnam)
--------------------------------------------- */
INSERT INTO stores (name, address, city, postal_code, country, phone, latitude, longitude, opening_hours) VALUES
('Sneaker Corner Hanoi', '25 Trang Tien Street, Hoan Kiem', 'Hanoi', '100000', 'Vietnam', '+84 24 3825 1234', 21.024520, 105.852547, 'Lun-Sam: 9h-21h, Dim: 10h-20h'),
('Sneaker Corner Ho Chi Minh', '135 Nguyen Hue Boulevard, District 1', 'Ho Chi Minh City', '700000', 'Vietnam', '+84 28 3821 5678', 10.773831, 106.701759, 'Lun-Sam: 9h-22h, Dim: 10h-21h'),
('Sneaker Corner Da Nang', '88 Bach Dang Street, Hai Chau', 'Da Nang', '550000', 'Vietnam', '+84 236 382 9012', 16.068083, 108.224761, 'Lun-Sam: 9h-21h, Dim: 10h-20h');

/* IDs magasins */
SET @store_hanoi := (SELECT store_id FROM stores WHERE city='Hanoi');
SET @store_hcm := (SELECT store_id FROM stores WHERE city='Ho Chi Minh City');
SET @store_danang := (SELECT store_id FROM stores WHERE city='Da Nang');

/* ---------------------------------------------
   INSERT COLORS (simplifiées)
--------------------------------------------- */
INSERT INTO colors (name, slug) VALUES
('Black', 'black'),
('White', 'white'),
('Gray', 'gray'),
('Red', 'red'),
('Blue', 'blue'),
('Green', 'green'),
('Yellow', 'yellow'),
('Orange', 'orange'),
('Pink', 'pink'),
('Brown', 'brown'),
('Beige', 'beige'),
('Navy', 'navy'),
('Purple', 'purple');

/* Récupération des IDs couleurs */
SET @black  := (SELECT color_id FROM colors WHERE slug='black');
SET @white  := (SELECT color_id FROM colors WHERE slug='white');
SET @gray   := (SELECT color_id FROM colors WHERE slug='gray');
SET @red    := (SELECT color_id FROM colors WHERE slug='red');
SET @blue   := (SELECT color_id FROM colors WHERE slug='blue');
SET @green  := (SELECT color_id FROM colors WHERE slug='green');
SET @yellow := (SELECT color_id FROM colors WHERE slug='yellow');
SET @orange := (SELECT color_id FROM colors WHERE slug='orange');
SET @pink   := (SELECT color_id FROM colors WHERE slug='pink');
SET @brown  := (SELECT color_id FROM colors WHERE slug='brown');
SET @beige  := (SELECT color_id FROM colors WHERE slug='beige');
SET @navy   := (SELECT color_id FROM colors WHERE slug='navy');
SET @purple := (SELECT color_id FROM colors WHERE slug='purple');

/* =============================================
   NIKE PRODUCTS
============================================= */
INSERT INTO products (name, slug, brand, model, release_date) VALUES
('Nike P-6000', 'nike-p6000', 'Nike', 'P-6000', '2024-01-01'),
('Nike Air Zoom Pegasus', 'nike-air-zoom-pegasus', 'Nike', 'Pegasus', '2024-01-01'),
('Nike Air Force 1', 'nike-air-force-1', 'Nike', 'Air Force 1', '2024-01-15'),
('Nike Air Max 90', 'nike-air-max-90', 'Nike', 'Air Max 90', '2024-02-01'),
('Nike Dunk Low', 'nike-dunk-low', 'Nike', 'Dunk Low', '2024-03-01'),
('Nike Air Jordan 1', 'nike-air-jordan-1', 'Nike', 'Air Jordan 1', '2024-01-20'),
('Nike Blazer Mid', 'nike-blazer-mid', 'Nike', 'Blazer Mid', '2024-04-01'),
('Nike Air Max 97', 'nike-air-max-97', 'Nike', 'Air Max 97', '2024-05-15');

SET @p6000 := (SELECT product_id FROM products WHERE slug='nike-p6000');
SET @pegasus := (SELECT product_id FROM products WHERE slug='nike-air-zoom-pegasus');
SET @af1 := (SELECT product_id FROM products WHERE slug='nike-air-force-1');
SET @am90 := (SELECT product_id FROM products WHERE slug='nike-air-max-90');
SET @dunk := (SELECT product_id FROM products WHERE slug='nike-dunk-low');
SET @aj1 := (SELECT product_id FROM products WHERE slug='nike-air-jordan-1');
SET @blazer := (SELECT product_id FROM products WHERE slug='nike-blazer-mid');
SET @am97 := (SELECT product_id FROM products WHERE slug='nike-air-max-97');

/* =============================================
   ADIDAS PRODUCTS
============================================= */
INSERT INTO products (name, slug, brand, model, release_date) VALUES
('Adidas Superstar', 'adidas-superstar', 'Adidas', 'Superstar', '2024-01-10'),
('Adidas Stan Smith', 'adidas-stan-smith', 'Adidas', 'Stan Smith', '2024-02-15'),
('Adidas Gazelle', 'adidas-gazelle', 'Adidas', 'Gazelle', '2024-03-20'),
('Adidas Samba', 'adidas-samba', 'Adidas', 'Samba', '2024-04-10'),
('Adidas Ultraboost', 'adidas-ultraboost', 'Adidas', 'Ultraboost', '2024-05-01'),
('Adidas NMD R1', 'adidas-nmd-r1', 'Adidas', 'NMD R1', '2024-06-01');

SET @superstar := (SELECT product_id FROM products WHERE slug='adidas-superstar');
SET @stansmith := (SELECT product_id FROM products WHERE slug='adidas-stan-smith');
SET @gazelle := (SELECT product_id FROM products WHERE slug='adidas-gazelle');
SET @samba := (SELECT product_id FROM products WHERE slug='adidas-samba');
SET @ultraboost := (SELECT product_id FROM products WHERE slug='adidas-ultraboost');
SET @nmd := (SELECT product_id FROM products WHERE slug='adidas-nmd-r1');

/* =============================================
   PUMA PRODUCTS
============================================= */
INSERT INTO products (name, slug, brand, model, release_date) VALUES
('Puma Suede Classic', 'puma-suede-classic', 'Puma', 'Suede Classic', '2024-01-25'),
('Puma RS-X', 'puma-rs-x', 'Puma', 'RS-X', '2024-02-20'),
('Puma Cali', 'puma-cali', 'Puma', 'Cali', '2024-03-15'),
('Puma Speedcat', 'puma-speedcat', 'Puma', 'Speedcat', '2024-04-20'),
('Puma Palermo', 'puma-palermo', 'Puma', 'Palermo', '2024-05-10');

SET @suede := (SELECT product_id FROM products WHERE slug='puma-suede-classic');
SET @rsx := (SELECT product_id FROM products WHERE slug='puma-rs-x');
SET @cali := (SELECT product_id FROM products WHERE slug='puma-cali');
SET @speedcat := (SELECT product_id FROM products WHERE slug='puma-speedcat');
SET @palermo := (SELECT product_id FROM products WHERE slug='puma-palermo');

/* =============================================
   NEW BALANCE PRODUCTS
============================================= */
INSERT INTO products (name, slug, brand, model, release_date) VALUES
('New Balance 574', 'new-balance-574', 'New Balance', '574', '2024-01-05'),
('New Balance 990v5', 'new-balance-990v5', 'New Balance', '990v5', '2024-02-10'),
('New Balance 550', 'new-balance-550', 'New Balance', '550', '2024-03-25'),
('New Balance 2002R', 'new-balance-2002r', 'New Balance', '2002R', '2024-04-15'),
('New Balance 327', 'new-balance-327', 'New Balance', '327', '2024-05-20');

SET @nb574 := (SELECT product_id FROM products WHERE slug='new-balance-574');
SET @nb990 := (SELECT product_id FROM products WHERE slug='new-balance-990v5');
SET @nb550 := (SELECT product_id FROM products WHERE slug='new-balance-550');
SET @nb2002r := (SELECT product_id FROM products WHERE slug='new-balance-2002r');
SET @nb327 := (SELECT product_id FROM products WHERE slug='new-balance-327');

/* =============================================
   VANS PRODUCTS
============================================= */
INSERT INTO products (name, slug, brand, model, release_date) VALUES
('Vans Old Skool', 'vans-old-skool', 'Vans', 'Old Skool', '2024-01-12'),
('Vans Sk8-Hi', 'vans-sk8-hi', 'Vans', 'Sk8-Hi', '2024-02-18'),
('Vans Authentic', 'vans-authentic', 'Vans', 'Authentic', '2024-03-08'),
('Vans Era', 'vans-era', 'Vans', 'Era', '2024-04-05'),
('Vans Slip-On', 'vans-slip-on', 'Vans', 'Slip-On', '2024-05-12');

SET @oldskool := (SELECT product_id FROM products WHERE slug='vans-old-skool');
SET @sk8hi := (SELECT product_id FROM products WHERE slug='vans-sk8-hi');
SET @authentic := (SELECT product_id FROM products WHERE slug='vans-authentic');
SET @era := (SELECT product_id FROM products WHERE slug='vans-era');
SET @slipon := (SELECT product_id FROM products WHERE slug='vans-slip-on');

/* =============================================
   CONVERSE PRODUCTS
============================================= */
INSERT INTO products (name, slug, brand, model, release_date) VALUES
('Converse Chuck Taylor All Star', 'converse-chuck-taylor', 'Converse', 'Chuck Taylor All Star', '2024-01-08'),
('Converse Chuck 70', 'converse-chuck-70', 'Converse', 'Chuck 70', '2024-02-22'),
('Converse One Star', 'converse-one-star', 'Converse', 'One Star', '2024-03-18'),
('Converse Run Star Hike', 'converse-run-star-hike', 'Converse', 'Run Star Hike', '2024-04-25'),
('Converse Jack Purcell', 'converse-jack-purcell', 'Converse', 'Jack Purcell', '2024-05-30');

SET @chuck := (SELECT product_id FROM products WHERE slug='converse-chuck-taylor');
SET @chuck70 := (SELECT product_id FROM products WHERE slug='converse-chuck-70');
SET @onestar := (SELECT product_id FROM products WHERE slug='converse-one-star');
SET @runstar := (SELECT product_id FROM products WHERE slug='converse-run-star-hike');
SET @jackpurcell := (SELECT product_id FROM products WHERE slug='converse-jack-purcell');

/* =============================================
   PRODUCT VARIANTS - NIKE
============================================= */
INSERT INTO product_variants (product_id, color_id, variant_name, price) VALUES
/* P-6000 */
(@p6000, @gray, 'Nike P-6000 Silver', 160.00),
(@p6000, @black, 'Nike P-6000 Black', 160.00),
/* Air Zoom Pegasus */
(@pegasus, @blue, 'Nike Air Zoom Pegasus Blue', 119.99),
(@pegasus, @gray, 'Nike Air Zoom Pegasus Gray', 119.99),
/* Air Force 1 */
(@af1, @white, 'Nike Air Force 1 White', 119.99),
(@af1, @black, 'Nike Air Force 1 Black', 119.99),
(@af1, @red, 'Nike Air Force 1 Red', 129.99),
/* Air Max 90 */
(@am90, @white, 'Nike Air Max 90 White', 139.99),
(@am90, @black, 'Nike Air Max 90 Black', 139.99),
(@am90, @gray, 'Nike Air Max 90 Gray', 139.99),
(@am90, @blue, 'Nike Air Max 90 Blue', 149.99),
/* Dunk Low */
(@dunk, @white, 'Nike Dunk Low White', 109.99),
(@dunk, @black, 'Nike Dunk Low Black', 109.99),
(@dunk, @green, 'Nike Dunk Low Green', 119.99),
(@dunk, @red, 'Nike Dunk Low Red', 119.99),
/* Air Jordan 1 */
(@aj1, @red, 'Nike Air Jordan 1 Red', 179.99),
(@aj1, @black, 'Nike Air Jordan 1 Black', 179.99),
(@aj1, @blue, 'Nike Air Jordan 1 Blue', 179.99),
(@aj1, @white, 'Nike Air Jordan 1 White', 169.99),
/* Blazer Mid */
(@blazer, @white, 'Nike Blazer Mid White', 109.99),
(@blazer, @black, 'Nike Blazer Mid Black', 109.99),
(@blazer, @green, 'Nike Blazer Mid Green', 119.99),
/* Air Max 97 */
(@am97, @gray, 'Nike Air Max 97 Gray', 179.99),
(@am97, @black, 'Nike Air Max 97 Black', 179.99),
(@am97, @white, 'Nike Air Max 97 White', 179.99);

/* =============================================
   PRODUCT VARIANTS - ADIDAS
============================================= */
INSERT INTO product_variants (product_id, color_id, variant_name, price) VALUES
/* Superstar */
(@superstar, @white, 'Adidas Superstar White', 99.99),
(@superstar, @black, 'Adidas Superstar Black', 99.99),
(@superstar, @green, 'Adidas Superstar Green', 109.99),
/* Stan Smith */
(@stansmith, @white, 'Adidas Stan Smith White', 89.99),
(@stansmith, @green, 'Adidas Stan Smith Green', 89.99),
(@stansmith, @navy, 'Adidas Stan Smith Navy', 94.99),
/* Gazelle */
(@gazelle, @black, 'Adidas Gazelle Black', 99.99),
(@gazelle, @blue, 'Adidas Gazelle Blue', 99.99),
(@gazelle, @red, 'Adidas Gazelle Red', 99.99),
(@gazelle, @green, 'Adidas Gazelle Green', 99.99),
/* Samba */
(@samba, @white, 'Adidas Samba White', 109.99),
(@samba, @black, 'Adidas Samba Black', 109.99),
(@samba, @brown, 'Adidas Samba Brown', 119.99),
/* Ultraboost */
(@ultraboost, @black, 'Adidas Ultraboost Black', 189.99),
(@ultraboost, @white, 'Adidas Ultraboost White', 189.99),
(@ultraboost, @gray, 'Adidas Ultraboost Gray', 189.99),
(@ultraboost, @blue, 'Adidas Ultraboost Blue', 199.99),
/* NMD R1 */
(@nmd, @black, 'Adidas NMD R1 Black', 149.99),
(@nmd, @white, 'Adidas NMD R1 White', 149.99),
(@nmd, @red, 'Adidas NMD R1 Red', 159.99);

/* =============================================
   PRODUCT VARIANTS - PUMA
============================================= */
INSERT INTO product_variants (product_id, color_id, variant_name, price) VALUES
/* Suede Classic */
(@suede, @black, 'Puma Suede Classic Black', 79.99),
(@suede, @blue, 'Puma Suede Classic Blue', 79.99),
(@suede, @red, 'Puma Suede Classic Red', 79.99),
(@suede, @green, 'Puma Suede Classic Green', 79.99),
/* RS-X */
(@rsx, @white, 'Puma RS-X White', 119.99),
(@rsx, @black, 'Puma RS-X Black', 119.99),
(@rsx, @blue, 'Puma RS-X Blue', 129.99),
/* Cali */
(@cali, @white, 'Puma Cali White', 99.99),
(@cali, @black, 'Puma Cali Black', 99.99),
(@cali, @pink, 'Puma Cali Pink', 109.99),
/* Speedcat */
(@speedcat, @black, 'Puma Speedcat Black', 109.99),
(@speedcat, @red, 'Puma Speedcat Red', 109.99),
(@speedcat, @white, 'Puma Speedcat White', 109.99),
/* Palermo */
(@palermo, @white, 'Puma Palermo White', 89.99),
(@palermo, @blue, 'Puma Palermo Blue', 89.99),
(@palermo, @beige, 'Puma Palermo Beige', 94.99);

/* =============================================
   PRODUCT VARIANTS - NEW BALANCE
============================================= */
INSERT INTO product_variants (product_id, color_id, variant_name, price) VALUES
/* 574 */
(@nb574, @gray, 'New Balance 574 Gray', 89.99),
(@nb574, @navy, 'New Balance 574 Navy', 89.99),
(@nb574, @black, 'New Balance 574 Black', 89.99),
(@nb574, @red, 'New Balance 574 Red', 94.99),
/* 990v5 */
(@nb990, @gray, 'New Balance 990v5 Gray', 199.99),
(@nb990, @navy, 'New Balance 990v5 Navy', 199.99),
(@nb990, @black, 'New Balance 990v5 Black', 199.99),
/* 550 */
(@nb550, @white, 'New Balance 550 White', 129.99),
(@nb550, @green, 'New Balance 550 Green', 129.99),
(@nb550, @navy, 'New Balance 550 Navy', 129.99),
(@nb550, @red, 'New Balance 550 Red', 134.99),
/* 2002R */
(@nb2002r, @gray, 'New Balance 2002R Gray', 149.99),
(@nb2002r, @beige, 'New Balance 2002R Beige', 149.99),
(@nb2002r, @black, 'New Balance 2002R Black', 149.99),
/* 327 */
(@nb327, @white, 'New Balance 327 White', 99.99),
(@nb327, @green, 'New Balance 327 Green', 99.99),
(@nb327, @orange, 'New Balance 327 Orange', 104.99);

/* =============================================
   PRODUCT VARIANTS - VANS
============================================= */
INSERT INTO product_variants (product_id, color_id, variant_name, price) VALUES
/* Old Skool */
(@oldskool, @black, 'Vans Old Skool Black', 69.99),
(@oldskool, @white, 'Vans Old Skool White', 69.99),
(@oldskool, @navy, 'Vans Old Skool Navy', 74.99),
(@oldskool, @red, 'Vans Old Skool Red', 74.99),
/* Sk8-Hi */
(@sk8hi, @black, 'Vans Sk8-Hi Black', 79.99),
(@sk8hi, @white, 'Vans Sk8-Hi White', 79.99),
(@sk8hi, @red, 'Vans Sk8-Hi Red', 84.99),
/* Authentic */
(@authentic, @black, 'Vans Authentic Black', 54.99),
(@authentic, @white, 'Vans Authentic White', 54.99),
(@authentic, @red, 'Vans Authentic Red', 54.99),
(@authentic, @navy, 'Vans Authentic Navy', 54.99),
/* Era */
(@era, @black, 'Vans Era Black', 59.99),
(@era, @navy, 'Vans Era Navy', 59.99),
(@era, @gray, 'Vans Era Gray', 64.99),
/* Slip-On */
(@slipon, @black, 'Vans Slip-On Black', 54.99),
(@slipon, @white, 'Vans Slip-On White', 54.99);

/* =============================================
   PRODUCT VARIANTS - CONVERSE
============================================= */
INSERT INTO product_variants (product_id, color_id, variant_name, price) VALUES
/* Chuck Taylor All Star */
(@chuck, @black, 'Converse Chuck Taylor Black', 59.99),
(@chuck, @white, 'Converse Chuck Taylor White', 59.99),
(@chuck, @red, 'Converse Chuck Taylor Red', 59.99),
(@chuck, @navy, 'Converse Chuck Taylor Navy', 59.99),
(@chuck, @pink, 'Converse Chuck Taylor Pink', 64.99),
/* Chuck 70 */
(@chuck70, @black, 'Converse Chuck 70 Black', 89.99),
(@chuck70, @white, 'Converse Chuck 70 White', 89.99),
(@chuck70, @green, 'Converse Chuck 70 Green', 94.99),
/* One Star */
(@onestar, @black, 'Converse One Star Black', 79.99),
(@onestar, @white, 'Converse One Star White', 79.99),
(@onestar, @yellow, 'Converse One Star Yellow', 84.99),
/* Run Star Hike */
(@runstar, @black, 'Converse Run Star Hike Black', 109.99),
(@runstar, @white, 'Converse Run Star Hike White', 109.99),
/* Jack Purcell */
(@jackpurcell, @white, 'Converse Jack Purcell White', 74.99),
(@jackpurcell, @black, 'Converse Jack Purcell Black', 74.99),
(@jackpurcell, @navy, 'Converse Jack Purcell Navy', 79.99);

/* =============================================
   INSERT SIZES FOR ALL VARIANTS
============================================= */

/* Procedure pour insérer les tailles pour chaque variante */
INSERT INTO variant_sizes (variant_id, size, stock_quantity)
SELECT v.variant_id, s.size, FLOOR(5 + RAND() * 15)
FROM product_variants v
CROSS JOIN (
    SELECT '36' AS size UNION SELECT '37' UNION SELECT '38' UNION SELECT '39' 
    UNION SELECT '40' UNION SELECT '41' UNION SELECT '42' UNION SELECT '43' 
    UNION SELECT '44' UNION SELECT '45' UNION SELECT '46'
) s;

/* =============================================
   INSERT IMAGES FOR ALL VARIANTS
============================================= */

/* Images pour toutes les variantes Nike */
INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_1.jpg'),
       1, 1
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Nike';

INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_2.jpg'),
       0, 2
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Nike';

/* Images pour toutes les variantes Adidas */
INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_1.jpg'),
       1, 1
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Adidas';

INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_2.jpg'),
       0, 2
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Adidas';

/* Images pour toutes les variantes Puma */
INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_1.jpg'),
       1, 1
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Puma';

INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_2.jpg'),
       0, 2
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Puma';

/* Images pour toutes les variantes New Balance */
INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_1.jpg'),
       1, 1
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'New Balance';

INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_2.jpg'),
       0, 2
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'New Balance';

/* Images pour toutes les variantes Vans */
INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_1.jpg'),
       1, 1
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Vans';

INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_2.jpg'),
       0, 2
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Vans';

/* Images pour toutes les variantes Converse */
INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_1.jpg'),
       1, 1
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Converse';

INSERT INTO variant_images (variant_id, image_url, is_primary, display_order)
SELECT v.variant_id, 
       CONCAT('images/products/', LOWER(REPLACE(REPLACE(v.variant_name, ' ', '_'), '-', '_')), '_2.jpg'),
       0, 2
FROM product_variants v
JOIN products p ON v.product_id = p.product_id
WHERE p.brand = 'Converse';

/* =============================================
   INSERT PRODUCT CATEGORIES
============================================= */
/* Nike - Lifestyle/Basketball/Running */
INSERT INTO product_categories (product_id, category_id) VALUES
(@p6000, @cat_chaussures), (@p6000, @cat_lifestyle),
(@pegasus, @cat_chaussures), (@pegasus, @cat_running),
(@af1, @cat_chaussures), (@af1, @cat_lifestyle),
(@am90, @cat_chaussures), (@am90, @cat_lifestyle),
(@dunk, @cat_chaussures), (@dunk, @cat_lifestyle), (@dunk, @cat_skateboard),
(@aj1, @cat_chaussures), (@aj1, @cat_basketball),
(@blazer, @cat_chaussures), (@blazer, @cat_lifestyle),
(@am97, @cat_chaussures), (@am97, @cat_lifestyle);

/* Adidas - Lifestyle/Running */
INSERT INTO product_categories (product_id, category_id) VALUES
(@superstar, @cat_chaussures), (@superstar, @cat_lifestyle),
(@stansmith, @cat_chaussures), (@stansmith, @cat_lifestyle),
(@gazelle, @cat_chaussures), (@gazelle, @cat_lifestyle),
(@samba, @cat_chaussures), (@samba, @cat_lifestyle),
(@ultraboost, @cat_chaussures), (@ultraboost, @cat_running),
(@nmd, @cat_chaussures), (@nmd, @cat_lifestyle);

/* Puma - Lifestyle */
INSERT INTO product_categories (product_id, category_id) VALUES
(@suede, @cat_chaussures), (@suede, @cat_lifestyle),
(@rsx, @cat_chaussures), (@rsx, @cat_lifestyle),
(@cali, @cat_chaussures), (@cali, @cat_lifestyle),
(@speedcat, @cat_chaussures), (@speedcat, @cat_lifestyle),
(@palermo, @cat_chaussures), (@palermo, @cat_lifestyle);

/* New Balance - Running/Lifestyle */
INSERT INTO product_categories (product_id, category_id) VALUES
(@nb574, @cat_chaussures), (@nb574, @cat_lifestyle),
(@nb990, @cat_chaussures), (@nb990, @cat_running),
(@nb550, @cat_chaussures), (@nb550, @cat_lifestyle),
(@nb2002r, @cat_chaussures), (@nb2002r, @cat_lifestyle),
(@nb327, @cat_chaussures), (@nb327, @cat_lifestyle);

/* Vans - Skateboard */
INSERT INTO product_categories (product_id, category_id) VALUES
(@oldskool, @cat_chaussures), (@oldskool, @cat_skateboard),
(@sk8hi, @cat_chaussures), (@sk8hi, @cat_skateboard),
(@authentic, @cat_chaussures), (@authentic, @cat_skateboard),
(@era, @cat_chaussures), (@era, @cat_skateboard),
(@slipon, @cat_chaussures), (@slipon, @cat_lifestyle);

/* Converse - Lifestyle */
INSERT INTO product_categories (product_id, category_id) VALUES
(@chuck, @cat_chaussures), (@chuck, @cat_lifestyle),
(@chuck70, @cat_chaussures), (@chuck70, @cat_lifestyle),
(@onestar, @cat_chaussures), (@onestar, @cat_lifestyle),
(@runstar, @cat_chaussures), (@runstar, @cat_lifestyle),
(@jackpurcell, @cat_chaussures), (@jackpurcell, @cat_lifestyle);

/* =============================================
   INSERT STORE INVENTORY (échantillon)
============================================= */

/* Stock aléatoire pour quelques produits dans chaque magasin */
INSERT INTO store_inventory (store_id, variant_id, size_id, quantity)
SELECT 
    s.store_id,
    vs.variant_id,
    vs.size_id,
    FLOOR(1 + RAND() * 10)
FROM stores s
CROSS JOIN (
    SELECT variant_id, size_id 
    FROM variant_sizes 
    WHERE size IN ('40', '41', '42', '43', '44')
    ORDER BY RAND()
    LIMIT 100
) vs
WHERE s.is_active = 1
ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity);
