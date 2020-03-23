<?php

namespace App\Model\Orm;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


class ProductsLangsRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [ProductLang::class];
	}
}
