<?php

namespace App\Model\Orm\Users;

use App\Model\Orm\Roles\Role;
use App\Model\Orm\Roles;
use DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;
use Nextras\Orm\Relationships\OneHasMany;


/**
 * User
 *
 * @property int                        $id {primary}
 * @property string                     $name
 * @property string                     $email
 * @property string                     $password
 * @property boolean                    $active
 * @property string                     $confirmationCode
 * @property DateTimeImmutable          $created {default now}
 * @property DateTimeImmutable|NULL     $deleted
 * @property ManyHasMany|Role[]         $roles {m:m Role::$users, isMain=true}
 */
class User extends Entity
{

}