<?php

namespace App\Admin\Components;


use App\Model\Orm\Orm;
use App\Model\Services\CategoriesService;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;


class AdminEshopCategoriesControl extends Control
{

	public $translator;

	public $orm;

	public $categoriesService;


	public function __construct(Orm $orm, Translator $t, CategoriesService $cS)
	{
		$this->orm = $orm;
		$this->translator = $t;
		$this->categoriesService = $cS;
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/adminEshopCategories.latte');
		$this->template->categoriesService = $this->categoriesService;
		$this->template->categories = $this->orm->categories->findAdminTopCategories()->fetchAll();
		$this->template->render();
	}

}

interface IAdminEshopMenuControlFactory
{
	public function create(): AdminEshopCategoriesControl;
}