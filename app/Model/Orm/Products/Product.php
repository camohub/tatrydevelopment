<?php

namespace App\Model\Orm;

use App\Presenters\BasePresenter;
use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int                                   $id                {primary}
 * @property Product|NULL                          $parent            {m:1 Product::$allProducts}
 * @property ProductLang[]                         $langs             {1:m ProductLang::$product}
 * @property-read string                           $name              {virtual}
 * @property-read string                           $desc              {virtual}
 * @property float                                 $price
 * @property int|NULL                              $stock             {default NULL}
 * @property int                                   $status            {default 1}
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
 * @property OneHasMany|ProductParameter[]|NULL    $localParameters   {virtual}
 * @property ManyHasMany|Category[]|NULL           $categories        {m:m Category::$products}
 * @property OneHasMany|ProductImage[]|NULL        $images            {1:m ProductImage::$product}
 * @property ProductImage|NULL                     $mainImage         {virtual}
 */
class Product extends Entity
{
	const STATUS_PUBLISHED = 1;
	const STATUS_UNPUBLISHED = 2;
	const STATUS_DELETED = 3;


	public function getterName()
	{
		$lang = $this->langs->get()->getBy(['lang' => BasePresenter::$staticLocale]);
		if( !$lang || !$lang->name ) $lang = $this->langs->get()->getBy(['lang' => 'sk']);

		return $lang->name;
	}


	public function getterDesc()
	{
		$lang = $this->langs->get()->getBy(['lang' => BasePresenter::$staticLocale]);
		if( !$lang || !$lang->desc ) $lang = $this->langs->get()->getBy(['lang' => 'sk']);

		return $lang->desc;
	}


	public function getterAdminProducts()
	{
		return $this->allProducts->get()->findBy(['status!=' => self::STATUS_DELETED]);
	}


	public function getterProducts()
	{
		return $this->allProducts->get()->findBy(['status' => self::STATUS_PUBLISHED]);
	}


	public function getterLocalParameters()
	{
		return $this->parameters->get()->findBy(['lang' => BasePresenter::$staticLocale]);
	}


	public function getterMainImage()
	{
		return $this->images->get()->findBy(['main' => 1])->orderBy(['main' => 'DESC'])->fetch();
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