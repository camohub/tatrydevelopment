<?php

namespace App\Model\Orm;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;


class ProductsRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Product::class];
	}


	public function findAdminProducts()
	{
		return $this->findBy(['parent' => NULL])->orderBy(['priority' => 'ASC']);
	}


	public function findAdminSkProducts()
	{
		return $this->findBy(['parent' => NULL, 'this->langs->lang' => 'sk', 'status!=' => Product::STATUS_DELETED])->orderBy(['priority' => 'ASC']);
	}
}
