<?php

namespace App\Model\Orm;


use App\Presenters\BasePresenter;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int                              $id                 {primary}
 * @property Category|NULL                    $parent             {m:1 Category::$allCategories}
 * @property OneHasMany|Category[]            $allCategories      {1:m Category::$parent, orderBy=[priority=ASC], cascade=[persist, remove]}
 * @property OneHasMany|Category[]            $adminCategories    {virtual}
 * @property OneHasMany|Category[]            $categories         {virtual}
 * @property OneHasMany|CategoryLang[]        $langs              {1:m CategoryLang::$category, cascade=[persist, remove]}
 * @property int                              $priority           {default 1}
 * @property int                              $status             {default 1}
 * @property-read string                      $name               {virtual}
 *
 * @property ManyHasMany|Product[]|NULL       $products           {m:m Product::$categories, isMain=true}
 * @property ManyHasMany|Parameter[]|NULL     $parameters         {m:m Parameter::$categories}
 */
class Category extends Entity
{
	const STATUS_UNPUBLISHED = 1;
	const STATUS_PUBLISHED = 2;
	const STATUS_DELETED = 3;


	public function getterName()
	{
		$lang = $this->langs->get()->getBy(['lang' => BasePresenter::$staticLocale]);
		if( !$lang || !$lang->name ) $lang = $this->langs->get()->getBy(['lang' => 'sk']);

		return $lang->name;
	}


	public function getterAdminCategories()
	{
		return $this->allCategories->get()->findBy(['status!=' => self::STATUS_DELETED]);
	}


	public function getterCategories()
	{
		return $this->allCategories->get()->findBy(['status' => self::STATUS_PUBLISHED]);
	}

}