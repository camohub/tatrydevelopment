<?php

namespace App\Admin\Forms;


use App\Exceptions\DuplicateEntryException;
use App\Model\Orm\Orm;
use App\Model\Services\CategoriesService;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class CategoryCreateFormControl extends Control
{

	public $modalTitle = 'Vytvoriť kategóriu';

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
		$this->template->setFile( __DIR__ . '/categoryCreateForm.latte' );
		$this->template->render();
	}


	public function createComponentForm( string $name ): Form
	{
		$form = new Form();
		//$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.' );

		$langs = $this->translator->getAvailableLocales();

		$form->addGroup();

		$names = $form->addContainer( 'names', 'Názov kategórie' );
		foreach ( $langs as $lang )
		{
			$lang = strtolower(explode('_', $lang)[0]);

			$names->addText( $lang, 'Názov ' . $lang )
				->setRequired( 'Názov je povinné pole pre každý jazyk.' )
				->setAttribute( 'class', 'form-control' );
		}

		$form->addSelect( 'parent', 'Vyberte pozíciu', $this->categoriesService->categoriesToSelect() )
			->setPrompt('')
			->setAttribute( 'class', 'form-control' );

		$form->addSubmit( 'sbmt', 'Uložiť' )
			->setAttribute( 'class', 'btn btn-primary btn-sm' );

		$form->onSuccess[] = [$this, 'formSucceeded'];

		return $form;
	}


	public function formSucceeded( Form $form, $values )
	{
		$presenter = $form->getPresenter();
		$values = $presenter->isAjax() ? $form->getHttpData() : $values;

		try
		{
			$this->categoriesService->createCategory( $values );
		}
		catch ( DuplicateEntryException $e )
		{
			// TODO: Bolo by lepsie zobrazit presne v ktorom jazyku je chyba tj. vyhladat cez query ci v db taky nazov je.
			$form->addError('Kategória s vybraným názvom už existuje. Názov musí byť unikátny pre každý jazyk.');
			return $form;
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$form->addError('Pri ukladaní došlo k nečakanej chybe. Skúste to prosím ešte raz, alebo kontaktujte administrátora');
			return $form;
		}

		$presenter->flashMessage('Kategória bola vytvorená.', 'success');

		if( $presenter->isAjax() )
		{
			$presenter->showModal = FALSE;
			$presenter->redrawControl('sortableList');
			return;
		}

		$presenter->redirect(':Admin:Categories:default');
	}

}

interface ICategoryCreateFormControlFactory
{
	public function create($id = NULL): CategoryCreateFormControl;
}