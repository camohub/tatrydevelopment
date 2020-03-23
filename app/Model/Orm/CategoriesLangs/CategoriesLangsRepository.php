<?php

namespace App\Model\Orm;


use Nextras\Orm\Repository\Repository;


class CategoriesLangsRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [CategoryLang::class];
	}
}
