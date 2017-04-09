CREATE TABLE users(
	id_user int(11) unsigned NOT NULL,
    login varchar(20) not null,
    email varchar(35) not null,
    password varchar(255) not null,
    date_register datetime not null,
    gender char(1) not null,
    id_sponsor int(11) unsigned DEFAULT 0,
    rank tinyint(1) default 0
);

ALTER TABLE `users`
MODIFY `id_user` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE users
ADD CONSTRAINT chk_gender CHECK (gender = 'M' or gender ='K');

ALTER TABLE `users`
ADD UNIQUE KEY `login_UNIQUE` (`login`),
ADD UNIQUE KEY `email_UNIQUE` (`email`);
  
ALTER TABLE `users`
ADD CONSTRAINT `users_id_sponsor_fk1` FOREIGN KEY (`id_sponsor`) REFERENCES `users` (`id_user`);



CREATE TABLE users_data(
	id_user_data int(11) unsigned NOT NULL,
    name1 varchar(15) not null,
    name2 varchar(15),
    surname varchar(35) not null,
    identity_number char(11) not null,
    telephone char(9) not null,
    language char(2) not null,
    id_user int(11) unsigned not null
);

ALTER TABLE `users_data`
MODIFY `id_user_data` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `users_data`
ADD UNIQUE KEY `id_user_UNIQUE` (`id_user`),
ADD UNIQUE KEY `identity_number_UNIQUE` (`identity_number`);
  
ALTER TABLE `users_data`
ADD CONSTRAINT `users_data_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;



CREATE TABLE users_addresses(
	id_user_address int(11) unsigned NOT NULL,
    ulica varchar(35) not null,
    nr_house varchar(3) not null,
	nr_flat varchar(3),
	city varchar(35) not null,
	post_code char(6),
	country varchar(30) not null,
    id_user int(11) unsigned not null
);

ALTER TABLE `users_addresses`
MODIFY `id_user_address` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `users_addresses`
ADD UNIQUE KEY `id_user_UNIQUE` (`id_user`);
  
ALTER TABLE `users_addresses`
ADD CONSTRAINT `users_addresses_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;



CREATE TABLE products(
	id_product smallint unsigned NOT NULL,
    name varchar(50) not null,
    description varchar(300),
	price numeric(10,2),
	version varchar(3) not null,
	rating numeric(2,1),
	id_category tinyint unsigned not null,
	id_program	tinyint unsigned not null
);

ALTER TABLE `products`
MODIFY `id_product` smallint unsigned AUTO_INCREMENT PRIMARY KEY;



CREATE TABLE categories(
	id_category tinyint unsigned NOT NULL,
    name varchar(20) not null
);

ALTER TABLE `categories`
MODIFY `id_category` tinyint unsigned AUTO_INCREMENT PRIMARY KEY;



CREATE TABLE programs(
	id_program tinyint unsigned NOT NULL,
    name varchar(20) not null
);

ALTER TABLE `programs`
MODIFY `id_program` tinyint unsigned AUTO_INCREMENT PRIMARY KEY;



ALTER TABLE `products`
ADD CONSTRAINT `products_id_category_fk1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`),
ADD CONSTRAINT `products_id_program_fk1` FOREIGN KEY (`id_program`) REFERENCES `programs` (`id_program`);


CREATE TABLE orders(
	id_order int(11) unsigned NOT NULL,
    date_order datetime not null,
	price_overall numeric(6,2) not null,
	id_user int(11) unsigned not null,
	id_state tinyint unsigned not null,
	id_payment_method tinyint unsigned not null,
	id_sponsor int(11) unsigned not null
);

ALTER TABLE `orders`
MODIFY `id_order` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

CREATE TABLE states(
	id_state tinyint unsigned NOT NULL,
    name varchar(30) not null
);

ALTER TABLE `states`
MODIFY `id_state` tinyint unsigned AUTO_INCREMENT PRIMARY KEY;

CREATE TABLE payment_methods(
	id_payment_method tinyint unsigned NOT NULL,
    name varchar(30) not null
);

ALTER TABLE `payment_methods`
MODIFY `id_payment_method` tinyint unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `orders`
ADD CONSTRAINT `orders_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
ADD CONSTRAINT `orders_id_state_fk1` FOREIGN KEY (`id_state`) REFERENCES `states` (`id_state`),
ADD CONSTRAINT `orders_id_payment_fk1` FOREIGN KEY (`id_payment_method`) REFERENCES `payment_methods` (`id_payment_method`);



CREATE TABLE realization_methods(
	id_realization_method tinyint unsigned NOT NULL,
    name varchar(30) not null
);

ALTER TABLE `realization_methods`
MODIFY `id_realization_method` tinyint unsigned AUTO_INCREMENT PRIMARY KEY;



CREATE TABLE carts(
	id_cart int(11) unsigned NOT NULL,
    id_order int(11) unsigned not null,
	id_product smallint unsigned not null,
	id_realization_method tinyint unsigned NOT NULL
);

ALTER TABLE `carts`
MODIFY `id_cart` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `carts`
ADD CONSTRAINT `carts_id_user_fk1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
ADD CONSTRAINT `carts_id_state_fk1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`),
ADD CONSTRAINT `carts_id_payment_fk1` FOREIGN KEY (`id_realization_method`) REFERENCES `realization_methods` (`id_realization_method`);



CREATE TABLE tickets(
	id_ticket int(11) unsigned NOT NULL,
    title varchar(150) not null,
	text varchar(2500) not null,
	date_open datetime not null,
	date_close datetime null,
	id_user int(11) unsigned not null
);

ALTER TABLE `tickets`
MODIFY `id_ticket` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `tickets`
ADD CONSTRAINT `tickets_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;



CREATE TABLE messages(
	id_message int(11) unsigned NOT NULL,
	text varchar(2000) not null,
	date_message datetime null,
	id_ticket int(11) unsigned not null,
	id_user int(11) unsigned not null
);

ALTER TABLE `messages`
MODIFY `id_message` int(11) unsigned AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `messages`
ADD CONSTRAINT `messages_id_ticket_fk1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE,
ADD CONSTRAINT `messages_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;





