CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category(
    id int NOT NULL AUTO_INCREMENT,
    name char NOT NULL,
    PRIMARY KEY (id)
);
CREATE UNIQUE INDEX idx_category ON category(name);

CREATE TABLE users(
    id int NOT NULL AUTO_INCREMENT,
    name char NOT NULL,
    email char NOT NULL,
    password char NOT NULL,
    contacts varchar(200),
    avatar_img varchar(200),
    PRIMARY KEY (id)
);
CREATE UNIQUE INDEX idx_users_name ON users(name);
CREATE UNIQUE INDEX idx_users_email ON users(email);

CREATE TABLE lot(
    id int NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    category_id int NOT NULL,
    description varchar(1000),
    init_price int NOT NULL,
    step int NOT NULL,
    final_date DATE NOT NULL,
    PRIMARY KEY (id)
);
CREATE INDEX idx_lot_name ON lot(name);

CREATE TABLE lots_img(
    id int NOT NULL AUTO_INCREMENT,
    image varchar(200) NOT NULL,
    lot_id int NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE bid(
    id int NOT NULL AUTO_INCREMENT,
    user_id int NOT NULL,
    bid_value int NOT NULL,
    bid_time TIMESTAMP NOT NULL,
    PRIMARY KEY (id)
);





