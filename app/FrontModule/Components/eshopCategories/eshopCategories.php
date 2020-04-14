<?php

namespace App\Front\Components;


use App\Model\Orm\Orm;
use App\Model\Services\CategoriesService;
use Kdyby\Translation\Translator;
use	Nette\Application\UI\Control;
use Nette\Caching\Cache;


class EshopCategoriesControl extends Control
{

	/** @var Orm */
	protected $orm;

	/** @var CategoriesService */
	protected $categoriesService;

	/** @var  Cache */
	protected $cache;

	/** @var  Translator */
	protected $translator;

	/**
	 * @desc Is id of current category.
	 * @var  int
	 */
	public $current_id = -1;  // null can fail.

	/**
	 * @desc Displays only visible categories if FALSE.
	 * @var  bool
	 */
	public $admin = FALSE;

	/**
	 * @desc Is used if we want to display only one section.
	 * @var null
	 */
	public $only_category = NULL;


	public function __construct(Orm $orm, CategoriesService $cS, Translator $t)
	{
		$this->orm = $orm;
		$this->categoriesService = $cS;
		$this->translator = $t;
	}


	/**
	 * returns Nette\Application\UI\ITemplate
	 */
	public function render()
	{
		$template = $this->template;
		$template->setFile( __DIR__ . '/eshopCategories.latte' );

		$template->categories = $this->orm->categories->findBy([ 'parent' => $this->only_category ])->orderBy(['priority' => 'ASC']);

		$template->current_id = $this->current_id;
		$template->lang_code = $this->translator->getLocale();
		$template->categoriesRepository = $this->orm->categories;
		$template->categoriesService = $this->categoriesService;

		$template->render();
	}


	/**
	 * @desc This sets the current category
	 * @param $id
	 */
	public function setCategory( $id )
	{
		$this->current_id = (int) $id;
	}


	/**
	 * @desc If is TRUE invisible categories will be displayed.
	 */
	public function setAdmin()
	{
		$this->admin = TRUE;
	}


	/**
	 * @desc This is used if we want to show only one category.
	 * @param $id
	 */
	public function setSection( $id )
	{
		$this->only_category = (int) $id;
	}

}



interface IEshopCategoriesControlFactory
{
	public function create(): EshopCategoriesControl;
}

