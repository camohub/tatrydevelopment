<?php

namespace App\Model\Orm;


use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\ManyHasOne;


/**
 * @property int                             $id                 {primary}
 * @property string                          $lang
 * @property string                          $name
 *
 * @property ManyHasOne|Parameter            $parameter          {m:1 Parameter::$langs}
 */
class ParameterLang extends Entity
{

}