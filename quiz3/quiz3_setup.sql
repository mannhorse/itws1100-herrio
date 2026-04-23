-- Run this once in phpMyAdmin to create the quiz scores table
CREATE TABLE IF NOT EXISTS `quiz3_scores` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `player_name`  VARCHAR(255) NOT NULL,
  `score`        INT NOT NULL,
  `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
