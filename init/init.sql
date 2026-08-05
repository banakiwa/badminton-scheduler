
SET NAMES utf8mb4;

ALTER DATABASE badminton_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

DROP TABLE IF EXISTS members;

CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('参加', '不参加', '未定') DEFAULT '未定',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

INSERT INTO members (name, attendance_date, status) VALUES 
('山田太郎', '2026-08-10', '参加'),
('鈴木花子', '2026-08-10', '不参加');