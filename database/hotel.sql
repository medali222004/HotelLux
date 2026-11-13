CREATE DATABASE IF NOT EXISTS hotel_db;
USE hotel_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('simple','double','suite') NOT NULL,
    price_per_night DECIMAL(8,2) NOT NULL,
    capacity INT NOT NULL,
    description TEXT,
    image VARCHAR(255),
    available BOOLEAN DEFAULT TRUE
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    total_price DECIMAL(10,2),
    status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

INSERT INTO rooms (name, type, price_per_night, capacity, description, image) VALUES
('Chambre Simple Vue Jardin', 'simple', 80.00, 1, 'Chambre confortable avec vue sur le jardin.', 'images/room1.jpg'),
('Chambre Simple Vue Ville', 'simple', 90.00, 1, 'Chambre moderne avec vue sur la ville.', 'images/room2.jpg'),
('Chambre Double Confort', 'double', 120.00, 2, 'Chambre spacieuse pour deux personnes.', 'images/room3.jpg'),
('Chambre Double Deluxe', 'double', 140.00, 2, 'Chambre luxueuse avec balcon.', 'images/room4.jpg'),
('Suite Royale', 'suite', 250.00, 4, 'Suite prestigieuse avec salon privé.', 'images/room5.jpg'),
('Suite Présidentielle', 'suite', 350.00, 6, 'La suite la plus luxueuse de l\'hôtel.', 'images/room6.jpg');

INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@hotel.com', '$2y$10$ioB6HikWfuiUeznat1RESOsTUgU19.7PnwNVp7qIVje6kL8Y3ow3y', 'admin'),
('Jean Dupont', 'jean@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Marie Curie', 'marie@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, status) VALUES
(2, 1, '2026-05-01', '2026-05-03', 160.00, 'confirmed'),
(3, 3, '2026-05-10', '2026-05-12', 240.00, 'pending');