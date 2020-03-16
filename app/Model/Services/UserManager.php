<?php


namespace App\Model;


use Nette;
use App;
use Nette\Security\Passwords;
use Nette\Utils\Random;
use Nextras\Orm\Entity\ToArrayConverter;
use Tracy\Debugger;


/**
 * Users management.
 * Do not use this class to manage users from social networks like FB
 */
class UserManager implements Nette\Security\IAuthenticator
{

	/** @var App\Model\Orm\Orm */
	private $orm;

	/** @var Passwords */
	private $passwords;


	public function __construct( App\Model\Orm\Orm $orm, Passwords $p )
	{
		$this->orm = $orm;
		$this->passwords = $p;
	}


	/**
	 * @param array $credentials
	 * @return Nette\Security\IIdentity
	 * @throws App\Exceptions\AccessDeniedException
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate( array $credentials ): Nette\Security\IIdentity
	{
		list( $name, $password ) = $credentials;

		/** @var App\Model\Orm\Users\User|NULL $user */
		$user = $this->orm->users->getBy( [ 'name' => $name, 'password!=' => NULL ] );

		if ( ! $user )
		{
			throw new Nette\Security\AuthenticationException( 'error', self::IDENTITY_NOT_FOUND );
		}
		elseif ( ! $user->active )
		{
			throw new App\Exceptions\AccessDeniedException;
		}
		elseif ( ! $this->passwords->verify( $password, $user->password ) )
		{
			throw new Nette\Security\AuthenticationException( 'error', self::INVALID_CREDENTIAL );
		}
		elseif ( $this->passwords->needsRehash( $user->password ) )
		{
			$user->password = $this->passwords->hash( $password );
			$this->orm->users->persistAndFlush($user);
		}

		$rolesArr = [];
		foreach ( $user->roles as $role )
		{
			$rolesArr[] = $role->name;
		}

		$userArr = $user->toArray(ToArrayConverter::RELATIONSHIP_AS_ARRAY);

		return new Nette\Security\Identity( $userArr['id'], $rolesArr, $userArr );
	}


	/**
	 * @desc Do not use it for users from social networks. They have its own manager classes.
	 * @param $params
	 * @param bool $admin
	 * @return Nette\Database\IRow
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function add( $params, $admin = FALSE )
	{
		// Do not use transacion here. It is used in RegisterPresenter

		// If $admin is false is possible create only role "registered".
		if ( ! $admin || ! isset( $params['roles'] ) )
		{
			$params['roles'] = [ 'registered' ];
		}

		$params['roles'] = $this->orm->roles->findBy( [ 'name' => $params['roles'] ] );

		$params[AclUsersRepository::COL_PASSWORD] = Passwords::hash( $params['password'] );
		$params['resource'] = 'App';

		// Do not use transacion here. It is used in RegisterPresenter
		$params['password'] = Passwords::hash($params['password']);
		$code = Random::generate( 10,'0-9a-zA-Z' );
		try
		{
			$row = $this->userRepository->insert([
				AclUsersRepository::COL_NAME => $params['user_name'],
				AclUsersRepository::COL_PASSWORD => $params['password'],
				AclUsersRepository::COL_EMAIL => $params['email'],
				AclUsersRepository::COL_ACTIVE => 0,
				AclUsersRepository::COL_CONFIRMATION_CODE => $code,
			]);
		}
		catch(\PDOException $e)
		{
			// This catch ONLY checks duplicate entry to fields with UNIQUE KEY
			$info = $e->errorInfo;
			// mysql==1062  sqlite==19  postgresql==23505
			if ( $info[0] == 23000 && $info[1] == 1062 )
			{
				// if/elseif returns the name of problematic field and value
				if( $this->aclUsersRepository->findOneBy( ['user_name = ?', $params['user_name']] ) )
				{
					$msg = 'user_name';	$code = 1;
				}
				elseif( $this->aclUsersRepository->findOneBy( ['email = ?', $params['email']] ) )
				{
					$msg = 'email';	$code = 2;
				}
				throw new App\Exceptions\DuplicateEntryException( $msg, $code );
			}
			else { throw $e; }
		}

		$this->aclUsersRolesRepository->insert( [AclUsersRolesRepository::COL_USERS_ID => $row->id, AclUsersRolesRepository::COL_ACL_ROLES_ID => 3] );

		return $row;

	}
}
