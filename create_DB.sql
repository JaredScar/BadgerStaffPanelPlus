-- Drop existing tables if they exist (in reverse dependency order)
DROP TABLE IF EXISTS `layouts`;
DROP TABLE IF EXISTS `notes`;
DROP TABLE IF EXISTS `commends`;
DROP TABLE IF EXISTS `bans`;
DROP TABLE IF EXISTS `kicks`;
DROP TABLE IF EXISTS `warns`;
DROP TABLE IF EXISTS `player_data`;
DROP TABLE IF EXISTS `players`;
DROP TABLE IF EXISTS `token_perms`;
DROP TABLE IF EXISTS `tokens`;
DROP TABLE IF EXISTS `staff_perms`;
DROP TABLE IF EXISTS `staff`;
DROP TABLE IF EXISTS `servers`;

-- Create tables
CREATE TABLE `servers` (
    `server_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `server_name` VARCHAR(255),
    `server_slug` VARCHAR(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `staff` (
    `staff_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `staff_username` VARCHAR(128) UNIQUE KEY,
    `password` VARCHAR(255),
    `staff_email` VARCHAR(255) UNIQUE KEY,
    `staff_discord` BIGINT(128),
    `server_id` INT(128),
    `role` VARCHAR(128) DEFAULT 'staff',
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `join_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `notes` TEXT,
    `last_active` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `staff_perms` (
    `staff_id` INT(128),
    `permission` SET('TOKEN_MANAGEMENT', 'STAFF_MANAGEMENT', 'SETTINGS_MANAGEMENT'),
    `allowed` BOOLEAN DEFAULT FALSE,
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `tokens` (
    `token_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `staff_id` INT(128),
    `note` VARCHAR(255),
    `token` VARCHAR(255),
    `active` BIT(1) DEFAULT 1,
    `deactivated_by` INT(128) DEFAULT NULL,
    `expires` DATETIME DEFAULT NULL,
    `active_flg` BIT(1) DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `token_perms` (
    `token_id` INT(128),
    `permission` SET('REGISTER', 'BAN_CREATE', 'BAN_DELETE', 'WARN_CREATE', 'WARN_DELETE', 'NOTE_CREATE', 'NOTE_DELETE', 'STAFF_CREATE', 'STAFF_DELETE', 'KICK_CREATE', 'KICK_DELETE', 'COMMEND_CREATE', 'COMMEND_DELETE', 'TRUSTSCORE_CREATE', 'TRUSTSCORE_DELETE', 'TRUSTSCORE_RESET'),
    `allowed` BIT(1) DEFAULT 0,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`token_id`, `permission`)
);

INSERT INTO `staff` (
                     `staff_id`,
                     `staff_username`,
                     `password`,
                     `staff_email`,
                     `staff_discord`,
                     `server_id`,
                     `role`,
                     `status`,
                     `join_date`,
                     `notes`,
                     `last_active`
                     ) VALUES (
                     1, -- Staff ID
                     'badger', -- Username
                     '$2a$15$ONynqN.bUe7SvpYhVksoqegQTCviThdqzCSsmoN/KmGwR61bmRQ5q', -- This is just "password" lol
                     'thewolfbadger@gmail.com', -- Email
                     394446211341615104, -- Discord ID
                     1, -- Server ID
                     'admin', -- Role
                     'active', -- Status
                     NOW(), -- Join Date
                     'System Administrator', -- Notes
                     NOW() -- Last Active
                     );

CREATE TABLE `players` (
    `server_id` INT(128) UNIQUE KEY,
    `player_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `discord_id` BIGINT(128),
    `game_license` VARCHAR(128) UNIQUE KEY,
    `steam_id` VARCHAR(32),
    `live` VARCHAR(128),
    `xbl` VARCHAR(128),
    `ip` VARCHAR(128),
    `last_player_name` VARCHAR(255),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `player_data` (
    `id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `server_id` INT(128) UNIQUE KEY,
    `player_id` INT(128) UNIQUE KEY,
    `playtime` INT(255),
    `trust_score` INT(255),
    `joins` INT(255),
    `last_join_date` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `warns` (
    `id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `server_id` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `kicks` (
    `id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `server_id` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `bans` (
    `id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `expires` INT(1),
    `expiredDate` DATETIME,
    `server_id` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `commends` (
    `id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `server_id` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `notes` (
    `id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `player_id` INT(128),
    `note` VARCHAR(255),
    `staff_id` INT(128),
    `server_id` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `layouts` (
    `staff_id` INT(128),
    `view` VARCHAR(128),
    `dashboard_name` VARCHAR(128) DEFAULT 'main',
    `widget_type` VARCHAR(128),
    `col` INT(128),
    `row` INT(128),
    `size_x` INT(128),
    `size_y` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

-- Sample layout data for the main dashboard
INSERT INTO `layouts` (
    `staff_id`,
    `view`,
    `dashboard_name`,
    `widget_type`,
    `col`,
    `row`,
    `size_x`,
    `size_y`,
    `created_at`,
    `updated_at`
) VALUES 
(1, 'dashboard', 'main', 'widget_notes', 0, 0, 6, 8, NOW(), NOW()),
(1, 'dashboard', 'main', 'widget_trust_scores', 6, 0, 6, 8, NOW(), NOW()),
(1, 'dashboard', 'main', 'widget_recent_activity', 0, 8, 12, 10, NOW(), NOW());

-- Sample layout data for a secondary dashboard
INSERT INTO `layouts` (
    `staff_id`,
    `view`,
    `dashboard_name`,
    `widget_type`,
    `col`,
    `row`,
    `size_x`,
    `size_y`,
    `created_at`,
    `updated_at`
) VALUES 
(1, 'dashboard', 'monitoring', 'widget_players', 0, 0, 6, 8, NOW(), NOW()),
(1, 'dashboard', 'monitoring', 'widget_all_players', 6, 0, 6, 8, NOW(), NOW()),
(1, 'dashboard', 'monitoring', 'records.widget_records', 0, 8, 12, 10, NOW(), NOW());
