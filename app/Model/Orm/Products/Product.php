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
 * @property int                                $id                {primary}
 * @property ProductLang[]                      $langs             {1:m ProductLang::$product}
 * @property float                              $price
 * @property-read string                        $name              {virtual}
 * @property int|NULL                           $stock             {default NULL}
 * @property DateTimeImmutable                  $created           {default now}
 * @property DateTimeImmutable                  $updated           {default now}
 * @property DateTimeImmutable                  $deleted           {default NULL}
 *
 * @property ManyHasMany|Category[]|NULL        $category          {m:m Category::$products}
 */
class Product extends Entity
{

	public function getterName()
	{
		$lang = $this->langs->get()->getBy(['lang' => BasePresenter::$staticLocale]);
		if( !$lang || !$lang->name ) $lang = $this->langs->get()->getBy(['lang' => 'sk']);

		return $lang->name;
	}
}