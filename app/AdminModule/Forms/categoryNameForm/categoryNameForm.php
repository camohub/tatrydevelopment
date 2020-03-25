<?php

namespace App\Admin\Forms;


use App\Exceptions\DuplicateEntryException;
use App\Model\Orm\Category;
use App\Model\Orm\Orm;
use App\Model\Services\CategoriesService;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class CategoryNameFormControl extends Control
{

	public $modalTitle = 'Zmeniť názov';

	public $id;

	/** @var Translator  */
	public $translator;

	/** @var  Orm */
	protected $orm;

	/** @var  CategoriesService */
	protected $categoriesService;


	public function __construct( $id, Orm $orm, CategoriesService $cS, Translator $t )
	{
		$this->id = $id;
		$this->orm = $orm;
		$this->categoriesService = $cS;
		$this->translator = $t;
	}


	public function render()
	{
		$this->getPresenter()->setReferer(self::class);
		$this->template->setFile( __DIR__ . '/categoryNameForm.latte' );
		$this->template->render();
	}


	public function createComponentForm( string $name ): Form
	{
		$form = new Form();
		//$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Požiadavka bola z bezpečnostných dôvodov zamietnutá.' );

		$names = $form->addContainer( 'names' );

		/** @var Category $category */
		$category = $this->orm->categories->getById( $this->id );

		foreach ( $category->langs as $lang )
		{
			$names->addText( $lang->lang, $lang->lang )
				->setRequired( 'Názov je povinné pole.' )
				->setAttribute( 'class', 'form-control' )
				->setDefaultValue( $lang->name );
		}

		$form->addHidden( 'id', $this->id );

		$form->addSubmit( 'sbmt', 'Premenovať' );

		$form->onSuccess[] = [$this, 'formSucceeded'];

		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{
		$presenter = $form->getPresenter();
		$values = $presenter->isAjax() ? $form->getHttpData() : $form->getValues();

		try
		{
			$this->categoriesService->updateName( $values['id'], (array)$values['names'] );
		}
		catch ( DuplicateEntryException $e )
		{
			$form->addError( 'Kategória s vybraným názvom už existuje. Názov musí byť unikátny pre každý jazyk.', 'error' );
			return $form;
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$form->addError( 'Pri ukladaní údajov došlo k nečakanej chybe. Skúste to prosím ešte raz, alebo kontaktujte administrátora', 'error' );
			return $form;
		}

		$presenter->flashMessage( 'Názov kategórie bol zmenený.', 'success' );

		if ( $presenter->isAjax() )
		{
			$presenter->showModal = FALSE;
			$presenter->redrawControl( 'sortableList' );
			return;
		}

		$presenter->redirect( ':Admin:Categories:default' );
	}

}

interface ICategoryNameFormControlFactory
{
	public function create($id = NULL): CategoryNameFormControl;
}