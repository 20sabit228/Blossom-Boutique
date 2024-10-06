CREATE DATABASE blossom_boutique;

USE blossom_boutique;

CREATE TABLE customers (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone INT(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    reg_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE products ( 
    product_id VARCHAR(30) PRIMARY KEY, 
    name VARCHAR(255) NOT NULL, 
    description TEXT NOT NULL, 
    price DECIMAL(10, 2) NOT NULL, 
    image_url VARCHAR(255) NOT NULL, 
    product_type VARCHAR(30) NOT NULL 
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    country VARCHAR(100) NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id VARCHAR(30) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);




-- indoor plants
INSERT INTO products (product_id, name, description, price, image_url, product_type) 
VALUES 
('IND001', 'Fiddle Leaf Fig', 'A beautiful indoor plant with large, leathery leaves.', 29.99, 'Images/fiddle_leaf.jpeg', 'indoor'),
('IND002', 'Snake Plant', 'Low maintenance indoor plant, perfect for low light conditions.', 19.99, 'Images/snake_plant.jpeg', 'indoor'),
('IND003', 'Peace Lily', 'An indoor plant known for its ability to clean the air.', 24.99, 'Images/peace_lily.jpg', 'indoor'),
('IND004', 'Spider Plant', 'A popular indoor plant with long, arching leaves and baby plants.', 14.99, 'Images/spider-plant.jpg', 'indoor'),
('IND005', 'ZZ Plant', 'Tough indoor plant that tolerates neglect and low light.', 22.99, 'Images/zz_plant.jpeg', 'indoor');


-- outdoor plants
INSERT INTO products (product_id, name, description, price, image_url, product_type) 
VALUES 
('OUT001', 'Rose Bush', 'A classic outdoor flowering plant with beautiful blooms.', 15.99, 'Images/rose-bush.jpg', 'outdoor'),
('OUT002', 'Lavender Plant', 'A fragrant plant known for its soothing aroma.', 12.99, 'Images/lavendar-plant.jpg', 'outdoor'),
('OUT003', 'Hibiscus', 'A tropical plant with large, showy flowers.', 18.99, 'Images/hibiscus.jpg', 'outdoor'),
('OUT004', 'Sunflower', 'Bright, cheerful flowers that thrive in full sun.', 9.99, 'Images/sunflower.jpg', 'outdoor'),
('OUT005', 'Boxwood Shrub', 'A hardy, evergreen shrub used for landscaping.', 25.99, 'Images/boxwood.jpg', 'outdoor');



