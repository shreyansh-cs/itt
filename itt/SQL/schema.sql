-- schema.sql
CREATE DATABASE itt_education;
USE itt_education;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    father_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20) UNIQUE NOT NULL,
    dob DATE NOT NULL,
    photo VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    user_type VARCHAR(50) DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- अन्य तालिकाएँ (जैसे, test_series, courses, notes आदि) भी आवश्यकता अनुसार जोड़ें।
