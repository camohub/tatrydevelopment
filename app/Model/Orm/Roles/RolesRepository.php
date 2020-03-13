<?php

namespace App\Model\Orm\Roles;


use Nextras\Orm\Repository\Repository;


class RolesRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Role::class];
	}
}
