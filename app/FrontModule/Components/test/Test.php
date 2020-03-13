<?php

namespace App\Front\Components;


class TestControl extends \Nette\Application\UI\Control
{

	public $id;

	public $translator;

	public function __construct($id = NULL, \Kdyby\Translation\Translator $t)
	{
		$this->translator = $t;
		$this->id = $id;
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/test.latte');
		$this->template->render();
	}

}

interface ITestControlFactory
{
	public function create($id = NULL): TestControl;
}