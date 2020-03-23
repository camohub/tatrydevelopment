<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class UsersRolesForeignKeys extends AbstractMigration
{
	/**
	* Migrate Up.
	*/
	public function up()
	{
		$this->query('
			ALTER TABLE `users_x_roles` ADD CONSTRAINT `users_x_roles_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
			ALTER TABLE `users_x_roles` ADD CONSTRAINT `users_x_roles_role_id_fk` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
		');

	}


	/**
	* Migrate Down.
	*/
	public function down()
	{
		$this->query('ALTER TABLE `users_x_roles` DROP INDEX  `users_x_roles_user_id_fk`');
		$this->query('ALTER TABLE `users_x_roles` DROP INDEX  `users_x_roles_role_id_fk`');
	}

}