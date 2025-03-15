-- Create database
CREATE DATABASE IF NOT EXISTS edupress_db;
USE edupress_db;

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50) NOT NULL,
    course_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create news table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category_id INT,
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample categories
INSERT INTO categories (name, icon, course_count) VALUES
('Web Development', 'fas fa-code', 25),
('Business', 'fas fa-briefcase', 18),
('Design', 'fas fa-palette', 15),
('Marketing', 'fas fa-bullhorn', 12),
('IT & Software', 'fas fa-laptop-code', 20),
('Photography', 'fas fa-camera', 8),
('Music', 'fas fa-music', 10),
('Language Learning', 'fas fa-language', 14);

-- Insert sample news
INSERT INTO news (title, description, image, author, category_id, views) VALUES
('Getting Started with Web Development', 'Learn the basics of web development including HTML, CSS, and JavaScript.', 'web-dev-intro.jpg', 'John Doe', 1, 150),
('Business Strategy Fundamentals', 'Understanding the key concepts of business strategy and management.', 'business-strategy.jpg', 'Jane Smith', 2, 120),
('UI/UX Design Principles', 'Master the principles of user interface and user experience design.', 'design-principles.jpg', 'Mike Wilson', 3, 200),
('Digital Marketing Trends 2024', 'Explore the latest trends in digital marketing and social media.', 'marketing-trends.jpg', 'Sarah Johnson', 4, 180),
('Cloud Computing Essentials', 'Introduction to cloud computing and its applications in modern IT.', 'cloud-computing.jpg', 'David Brown', 5, 160),
('Photography Basics Guide', 'Learn the fundamentals of photography and camera settings.', 'photo-basics.jpg', 'Emma Davis', 6, 90);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@edupress.com', 'Administrator'); 