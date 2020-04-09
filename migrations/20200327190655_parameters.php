<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class Categories extends AbstractMigration
{
	/**
	* Migrate Up.
	*/
	public function up()
	{
		$this->query('
			DROP TABLE IF EXISTS `parameters`;
			CREATE TABLE `parameters` (
				`id` int(10) UNSIGNED NOT NULL,
				`type` tinyint(3) UNSIGNED NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;
		');

		$this->query('ALTER TABLE `parameters` ADD PRIMARY KEY (`id`)');

		$this->query('ALTER TABLE `parameters` ADD FULLTEXT KEY `parameters_value_ftk` (`value`)');

		$this->query('ALTER TABLE `parameters` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT');


		$this->query('DROP TABLE IF EXISTS `parameters_langs`');

		$this->query('
			CREATE TABLE IF NOT EXISTS `parameters_langs` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`parameter_id` int(10) UNSIGNED NOT NULL,
				`lang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				`name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL
				PRIMARY KEY (`id`),
				KEY `parameters_langs_parameter_id_k` (`parameter_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci
		');

		$this->query('
			ALTER TABLE `parameters_langs` 
				ADD CONSTRAINT `parameters_langs_parameter_id_fk` FOREIGN KEY (`parameter_id`) REFERENCES `parameters` (`id`) 
				ON DELETE SET NULL ON UPDATE CASCADE;
		');

		$this->query('DROP TABLE IF EXISTS `products_parameters`');

		$this->query('
			CREATE TABLE IF NOT EXISTS `products_parameters` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`product_id` int(10) UNSIGNED DEFAULT NULL,
				`parameter_id` int(10) UNSIGNED DEFAULT NULL,
				`lang` varchar(10) COLLATE utf8mb4_slovak_ci NOT NULL,
				`value` varchar(250) COLLATE utf8mb4_slovak_ci NOT NULL,
				PRIMARY KEY (`id`),
				KEY `products_parameters_parameter_id_k` (`parameter_id`),
				KEY `products_parameters_product_id_k` (`product_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;
		');

		$this->query('
			ALTER TABLE `products_parameters` 
				ADD CONSTRAINT `products_parameters_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) 
				ON DELETE SET NULL ON UPDATE CASCADE
		');

		$this->query('
			ALTER TABLE `products_parameters` 
				ADD CONSTRAINT `products_parameters_parameter_id_fk` FOREIGN KEY (`parameter_id`) REFERENCES `parameters`(`id`) 
				ON DELETE SET NULL ON UPDATE CASCADE;
		');
	}


	/**
	* Migrate Down.
	*/
	public function down()
	{
		$this->query('DROP TABLE IF EXISTS `parameters_langs`');
		$this->query('DROP TABLE IF EXISTS `parameters`');
	}

}