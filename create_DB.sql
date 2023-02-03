CREATE TABLE `staff` (
    `staff_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `staff_username` VARCHAR(128),
    `staff_password` VARCHAR(255),
    `staff_email` VARCHAR(255),
    `staff_discord` INT(128),
    `server_id` INT(128),
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `players` (
    `server_id` INT(128) UNIQUE KEY,
    `player_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `discord_id` INT(128),
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
    `widget_type` VARCHAR(128),
    `col` INT(128),
    `row` INT(128),
    `size_x` INT(128),
    `size_y` INT(128)
);
