<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Images extends AbstractMigration
{
	/**
	* Migrate Up.
	*/
	public function up()
	{
		$this->query('DROP TABLE IF EXISTS `products_images`');

		$this->query('
			CREATE TABLE IF NOT EXISTS `products_images` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`product_id` int(10) UNSIGNED NOT NULL,
				`file` varchar(100) COLLATE utf8mb4_slovak_ci NOT NULL,
				`main` TINYINT UNSIGNED  DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `products_images_product_id_k` (`product_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query('
			ALTER TABLE `products_images`
  				ADD CONSTRAINT `products_images_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) 
  				ON DELETE CASCADE ON UPDATE CASCADE;
		');
	}


	/**
	* Migrate Down.
	*/
	public function down()
	{
		$this->query('DROP TABLE `products_images`');
	}

}