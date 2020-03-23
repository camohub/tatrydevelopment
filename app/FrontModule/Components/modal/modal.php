<?php

namespace App\Front\Components;


use Nette\Application\UI\Control;
use Nette\ComponentModel\Component;


/**
 * Class ModalControl
 * @package App\Front\Components
 *
 * Pouzitie v presenteri
 *
 * public function actionSignIn()
 * {
 * 		$this['modal']->addContentComponent($this['signInForm']);
 * 		$this->showModal = !$this->user->isLoggedIn();
 * 		$this->setView('default');
 * }
 *
 *
 */
class ModalControl extends Control
{

	public $controlFactory = NULL;


	public function __construct()
	{
		// Sem nepredávať showModal pretože sa môže zmeniť napr. po odoslaní formulára.
	}


	public function render()
	{
		$this->template->setFile(__DIR__ . '/modal.latte');
		$this->template->title =  $this->getComponent('contentComponent', FALSE)
			? ($this['contentComponent']->modalTitle ?? '')
			: '';
		$this->template->render();
	}


	public function addContentComponent(Component $component)
	{
		// Pôvodne som chcel predávať iba factory ale tam bol problém s predávaním parametrov do factory.
		// Takto sa perametre predávajú v presentery
		$component->setParent(NULL);
		$this->addComponent($component, 'contentComponent');
	}
}



interface IModalControlFactory
{
	public function create(): ModalControl;
	//public function create(Control $component): ModalControl;
	//public function create(): ModalControl;
}