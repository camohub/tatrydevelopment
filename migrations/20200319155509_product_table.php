<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ProductTable extends AbstractMigration
{
	/**
	* Migrate Up.
	*/
	public function up()
	{
		$this->query('SET FOREIGN_KEY_CHECKS=0');
		$this->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
		$this->query('SET time_zone = "+00:00"');

		$this->query('DROP TABLE IF EXISTS `products`;');

		$this->query('
			CREATE TABLE IF NOT EXISTS `products` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`parent` int(10) UNSIGNED DEFAULT NULL,
				`price` decimal(10,2) UNSIGNED NOT NULL,
				`stock` mediumint(9) DEFAULT NULL,
				`status` TINYINT UNSIGNED NOT NULL DEFAULT \'1\',
				`priority` INT UNSIGNED NOT NULL DEFAULT \'1\',
				`created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`deleted` datetime DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `products_parent_k` (`parent`),
				KEY `products_status_k` (`status`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;
		');

		$this->query('
			ALTER TABLE `products` ADD CONSTRAINT `products_parent_fk` FOREIGN KEY (`parent`) 
				REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
		');

		$this->query('
			DROP TABLE IF EXISTS `products_langs`;
			CREATE TABLE IF NOT EXISTS `products_langs` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`product_id` int(10) UNSIGNED NOT NULL, 
				`lang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				`name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				`desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `products_langs_lang_name_uk` (`name`,`lang`),
				KEY `products_langs_product_id_k` (`product_id`)
			) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;

			ALTER TABLE `products_langs` 
				ADD CONSTRAINT `products_langs_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) 
				ON DELETE CASCADE ON UPDATE CASCADE;
		');

		$this->query('
			INSERT INTO `products` (`id`, `price`, `stock`, `created`, `updated`, `deleted`) VALUES
				(1, 10.5, 10, \'2020-03-19 20:35:26\', \'2020-03-19 20:35:26\', NULL),
				(2, 5.3, 5, \'2020-03-19 20:35:26\', \'2020-03-19 20:35:26\', NULL);
		');

		$this->query('
			INSERT INTO `products_langs` (`product_id`, `lang`, `name`, `desc`) VALUES
				(1, \'sk\', \'sk nazov 1\', \'sk popis 1\'),
				(1, \'en\', \'en name 1\', \'en desc 1\'),
				(1, \'cs\', \'cs jmeno 1\', \'cs popis 1\'),
				(2, \'sk\', \'sk meno 2\', \'sk popis 2\'),
				(2, \'en\', \'en name 2\', \'en desc 2\'),
				(2, \'cs\', \'cs jmeno 2\', \'cs popis 2\');
		');

		$this->query('SET FOREIGN_KEY_CHECKS=1');

	}


	/**
	* Migrate Down.
	*/
	public function down()
	{
		$this->query('DROP TABLE products_langs');
		$this->query('DROP TABLE products');
	}

}