<?php

namespace App\Model\Orm;


use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;


/**
 * User
 *
 * @property int                        $id {primary}
 * @property string                     $name
 * @property ManyHasMany|User[]         $users {m:m User::$roles}
 */
class Role extends Entity
{

}