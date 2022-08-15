CREATE DATABASE yeticave DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE yeticave;
CREATE TABLE category(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(64) NOT NULL,
    symbol_code CHAR(64) NOT NULL
);

CREATE TABLE lot (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
winner_id INT NOT NULL,
category_id INT NOT NULL,
created_date DATE NOT NULL DEFAULT (CURRENT_DATE),
finished_date DATE NOT NULL,
name CHAR(64) NOT NULL,
description TEXT NOT NULL,
img_url CHAR(255) NOT NULL,
initial_price INT NOT NULL,
bid_step INT NOT NULL
);

CREATE TABLE bid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lot_id INT NOT NULL,
    created_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    price INT NOT NULL
);

CREATE TABLE user (
id INT AUTO_INCREMENT PRIMARY KEY,
created_date DATE NOT NULL DEFAULT (CURRENT_DATE),
email VARCHAR(128) NOT NULL UNIQUE,
name CHAR(64) NOT NULL,
password CHAR(64) NOT NULL,
contacts TEXT NOT NULL
);

ALTER TABLE bid
ADD FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
ADD FOREIGN KEY(lot_id) REFERENCES lot(id) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE lot
ADD FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
ADD FOREIGN KEY(winner_id) REFERENCES user(id) ON DELETE RESTRICT ON UPDATE RESTRICT,
ADD FOREIGN KEY(category_id) REFERENCES category(id) ON DELETE RESTRICT ON UPDATE RESTRICT;