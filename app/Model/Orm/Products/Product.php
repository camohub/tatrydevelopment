<?php

namespace App\Model\Orm;

use App\Presenters\BasePresenter;
use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Mapper\Dbal\DbalCollection;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;
use Tracy\Debugger;


/**
 * @property int                                   $id                {primary}
 * @property Product|NULL                          $parent            {m:1 Product::$allProducts}
 * @property ProductLang[]                         $langs             {1:m ProductLang::$product}
 * @property-read string                           $name              {virtual}
 * @property float                                 $price
 * @property int|NULL                              $stock             {default NULL}
 * @property int                                   $priority          {default 1}
 * @property DateTimeImmutable                     $created           {default now}
 * @property DateTimeImmutable                     $updated           {default now}
 * @property DateTimeImmutable|NULL                $deleted           {default NULL}
 *
 * @property OneHasMany|Product[]                  $allProducts       {1:m Product::$parent, orderBy=[priority=ASC], cascade=[persist, remove]}
 * @property OneHasMany|Product[]                  $adminProducts     {virtual}
 * @property OneHasMany|Product[]                  $products          {virtual}
 *
 * @property OneHasMany|ProductParameter[]|NULL    $parameters        {1:m ProductParameter::$product, cascade=[persist, remove]}
 * @property ManyHasMany|Category[]|NULL           $categories        {m:m Category::$products}
 * @property OneHasMany|ProductImage[]|NULL        $images            {1:m ProductImage::$product}
 */
class Product extends Entity
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


	public function getterAdminProducts()
	{
		return $this->allProducts->get()->findBy(['status!=' => self::STATUS_DELETED]);
	}


	public function getterProducts()
	{
		return $this->allProducts->get()->findBy(['status' => self::STATUS_PUBLISHED]);
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	/////// METHODS WITH PARAMS ////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////
	public function getName($lang)
	{
		$lang = $this->langs->get()->getBy(['lang' => $lang]);
		return $lang ? $lang->name : NULL;
	}


	public function getDesc($lang)
	{
		$lang = $this->langs->get()->getBy(['lang' => $lang]);
		return $lang ? $lang->desc : NULL;
	}
}