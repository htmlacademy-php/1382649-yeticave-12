CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category(
    id int NOT NULL AUTO_INCREMENT,
    name char(50) NOT NULL,
    PRIMARY KEY (id)
);
CREATE UNIQUE INDEX idx_category ON category(name);

CREATE TABLE user(
    id int NOT NULL AUTO_INCREMENT,
    name char(80) NOT NULL,
    email char(80) NOT NULL,
    password char(50) NOT NULL,
    contacts varchar(200),
    avatar_img varchar(200),
    PRIMARY KEY (id)
);
CREATE INDEX idx_user_name ON user(name);
CREATE UNIQUE INDEX idx_user_email ON user(email);

CREATE TABLE lot(
    id int NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    category_id int NOT NULL,
    description varchar(1000),
    init_price int NOT NULL,
    step int,
    final_date DATE NOT NULL,
    PRIMARY KEY (id)

);
CREATE INDEX idx_lot_name ON lot(name);

CREATE TABLE lot_img(
    id int NOT NULL AUTO_INCREMENT,
    image_url varchar(200) NOT NULL,
    lot_id int NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE bid(
    id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    lot_id int,
    bid_value int NOT NULL,
    bid_time TIMESTAMP NOT NULL DEFAULT NOW(),
    PRIMARY KEY (id)
);

ALTER TABLE user MODIFY password CHAR(60);

CREATE FULLTEXT INDEX lots_search ON lot(name,description);

ALTER TABLE lot
ADD COLUMN closed int (1) AFTER final_date;

UPDATE lot
SET closed = 0 WHERE closed IS NULL;
