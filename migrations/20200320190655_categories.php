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
		$this->query('DROP TABLE IF EXISTS `categories`');

		$this->query('
			CREATE TABLE `categories` (
				 `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				 `parent` int(10) UNSIGNED DEFAULT NULL,
				 `priority` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
				 `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
				 PRIMARY KEY (`id`),
				 KEY `categories_parent_k` (`parent`),
				 KEY `categories_order_k` (`priority`),
				 KEY `categories_status_k` (`status`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;
		');

		$this->query('DROP TABLE IF EXISTS `categories_langs`');

		$this->query('
			CREATE TABLE `categories_langs` (
				`id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`category_id` int(10) UNSIGNED NOT NULL,
				`lang` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				`name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_slovak_ci NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `categories_langs_name_lang_uk` (`name`,`lang`),
				KEY `categories_langs_category_id_k` (`category_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_slovak_ci;
		');

		$this->query('
			ALTER TABLE `categories` ADD CONSTRAINT `categories_paretn_fk` FOREIGN KEY (`parent`) 
				REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
		');

		$this->query('
			ALTER TABLE `categories_langs` ADD CONSTRAINT `categories_langs_category_id_fk` FOREIGN KEY (`category_id`) 
				REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
		');

		$this->query('
			INSERT INTO `categories` (`id`, `parent`, `priority`, `status`) VALUES
				(3, NULL, 1, 1),
				(4, NULL, 2, 1),
				(5, NULL, 3, 1),
				(6, 4, 1, 1),
				(7, 4, 2, 1)
		');

		$this->query('
			INSERT INTO `categories_langs` (`category_id`, `lang`, `name`) VALUES
				(3, \'sk\', \'item 3\'),
				(4, \'sk\', \'item 4\'),
				(5, \'sk\', \'item 5\'),
				(6, \'sk\', \'item 6 parent 4\'),
				(7, \'sk\', \'item 7 parent 4\');
		');
	}


	/**
	* Migrate Down.
	*/
	public function down()
	{
		$this->query('DROP TABLE IF EXISTS `categories_langs`');
		$this->query('DROP TABLE IF EXISTS `categories`');
	}

}