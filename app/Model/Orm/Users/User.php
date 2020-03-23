<?php

namespace App\Model\Orm;

use DateTimeImmutable;
use Nextras\Orm\Entity\Entity;
use Nextras\Orm\Relationships\ManyHasMany;


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