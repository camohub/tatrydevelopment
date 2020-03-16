<?php

namespace App\Model\Orm\Products;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


class ProductsRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Product::class];
	}
}