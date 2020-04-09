<?php

namespace App\Model\Orm;


use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * @property int                                  $id                 {primary}
 * @property int                                  $type
 *
 * @property ManyHasMany|Category[]|NULL          $categories                {m:m Category::$parameters, isMain=true}
 * @property OneHasMany|ParameterLang[]|NULL      $langs                     {1:m ParameterLang::$parameter, cascade=[persist, remove]}
 * @property OneHasMany|ProductParameter[]|NULL   $productsParameters        {1:m ProductParameter::$parameter, cascade=[persist, remove]}
 */
class Parameter extends Entity
{
	const TYPE_STRING = 1;
	const TYPE_BOOLEAN = 2;
}