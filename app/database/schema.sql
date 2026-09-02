CREATE TABLE IF NOT EXISTS categories
(
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL,
	slug VARCHAR(255) UNIQUE NOT NULL,
	parent_id INT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	update_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO categories (name,slug,parent_id) VALUES 
('Electrónica', 'electronica', NULL),
('Computadoras', 'computadoras', 1),
('Accesorios', 'accesorios', 1),
('Hogar', 'hogar', NULL),
('Cocina', 'cocina', 4),
('Oficina', 'oficina', NULL),
('Deportes', 'deportes', NULL);ocker 