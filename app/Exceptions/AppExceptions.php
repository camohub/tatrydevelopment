<?php

namespace App\Exceptions;

use Exception;
use Nette;


/**
 * @desc try to insert duplicate value to fields with unique key
 * Class DuplicateEntryException
 * @package App\Exceptions
 */
class DuplicateEntryException extends \Exception {}

/**
 * @desc user have not premission to view or do something
 * Class AccessDeniedException
 * @package App\Exceptions
 */
class AccessDeniedException extends \Exception {}

/**
 * @desc parameter suplied as an argument is wrong (typehint/range)
 * Class InvalidArgumentException
 * @package App\Exceptions
 */
class InvalidArgumentException extends \Exception {}

/**
 * @desc something goes wrong with confirmation emails. (Ie. users acount is active, but email is not confirmed.)
 * Class ConfirmationEmailException
 * @package App\Exceptions
 */
class ConfirmationEmailException extends \Exception {}

/**
 * @desc something goes wrong but we don't know what.
 * Class GeneralException
 * @package App\Exceptions
 */
class GeneralException extends \Exception {}

/**
 * @desc While category deletion if category contains products
 * Class CategoryContainsProductsException
 * @package App\Exceptions
 */
class CategoryContainsProductsException extends \Exception
{
	public function __construct( $message = "", $categoryName )
	{
		parent::__construct( $message, 0, NULL );
		$this->categoryName = $categoryName;
	}

	public $categoryName = '';
}



