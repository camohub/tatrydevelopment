<?php

namespace App\Model\Orm;


use Nextras\Orm\Repository\Repository;


class CategoriesRepository extends Repository
{

	static function getEntityClassNames(): array
	{
		return [Category::class];
	}


	public function findAdminTopCategories()
	{
		return $this->findBy(['parent' => NULL])->orderBy(['priority' => 'ASC']);
	}
}
