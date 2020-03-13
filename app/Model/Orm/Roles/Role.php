<?php

namespace App\Model\Orm\Roles;


use App\Model\Orm\Users\User;
use Nextras\Orm\Entity\Entity;


/**
 * User
 *
 * @property int                        $id {primary}
 * @property string                     $name
 * @property User[]                     $users {m:m User::$roles}
 */
class Role extends Entity
{

}