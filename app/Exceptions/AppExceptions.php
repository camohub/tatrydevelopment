<?php

namespace App\Exceptions;

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





