CREATE TABLE `staff` (
    `staff_id` INT(128) AUTO_INCREMENT PRIMARY KEY,
    `staff_username` VARCHAR(128),
    `staff_password` VARCHAR(255),
    `staff_email` VARCHAR(255),
    `staff_discord` INT(128),
    `server_id` INT(128)
);

CREATE TABLE `players` (
    `server_id` INT(128) PRIMARY KEY,
    `player_id` INT(128) AUTO_INCREMENT UNIQUE KEY,
    `discord_id` INT(128),
    `game_license` VARCHAR(128) PRIMARY KEY,
    `steam_id` VARCHAR(32),
    `live` VARCHAR(128),
    `xbl` VARCHAR(128),
    `ip` VARCHAR(128),
    `last_player_name` VARCHAR(255)
);

CREATE TABLE `player_data` (
    `server_id` INT(128) PRIMARY KEY,
    `player_id` INT(128) PRIMARY KEY,
    `playtime` INT(255),
    `trust_score` INT(255),
    `joins` INT(255),
    `last_join_date` DATETIME
);

CREATE TABLE `kicks` (
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `datetime_recorded` DATETIME,
    `server_id` INT(128)
);

CREATE TABLE `bans` (
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `expires` INT(1),
    `expiredDate` DATETIME,
    `datetime_recorded` DATETIME,
    `server_id` INT(128)
);

CREATE TABLE `commends` (
    `player_id` INT(128),
    `reason` VARCHAR(255),
    `staff_id` INT(128),
    `datetime_recorded` DATETIME,
    `server_id` INT(128)
);

CREATE TABLE `notes` (
    `player_id` INT(128),
    `note` VARCHAR(255),
    `staff_id` INT(128),
    `datetime_recorded` DATETIME,
    `server_id` INT(128)
);
