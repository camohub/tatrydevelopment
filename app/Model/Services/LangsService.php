<?php


namespace App\Model\Services;




class LangsService
{

	/** @var array */
	protected $langs;


	/**
	 * LangsService constructor.
	 * @param array $langs
	 */
	public function __construct( array $langs )
	{
		$this->langs = $langs;
	}


	public function getLangs()
	{
		return $this->langs;
	}

}
