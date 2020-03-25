<?php

namespace App\Front\Components;


use Nette\Application\UI\Control;
use Nette\ComponentModel\Component;
use Tracy\Debugger;


/**
 * Class ModalControl
 * @package App\Front\Components
 *
 * Pouzitie v presenteri
 *
 * public function actionSignIn()
 * {
 * 		$this->showModal = !$this->user->isLoggedIn();
 * 		$this['modal']->addContentComponent($this['signInForm']);
 * 		$this->setView('default');
 * }
 *
 * Pouzitie v presenteri s ajaxom
 *
 * 	public function actionCategories( $id = NULL )
 * {
 *		$this->showModal = TRUE;
 * 		$this['modal']->addContentComponent($this['editCategoryNameForm']);
 * 		if( $this->isAjax() ) $this['modal']->redrawControl();  // Toto nieje treba. Vola sa v BasePresenter::beforeRender()
 * }
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
		$this->template->showModal = $this->presenter->showModal;
		$this->template->title =  $this->getComponent('contentComponent', FALSE)
			? ($this['contentComponent']->modalTitle ?? '')
			: '';
		$this->template->render();
	}


	public function addContent(Component $component)
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