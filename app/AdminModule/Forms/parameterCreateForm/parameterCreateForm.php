<?php

namespace App\Admin\Forms;


use App\Exceptions\DuplicateEntryException;
use App\Model\Orm\Orm;
use App\Model\Orm\Parameter;
use App\Model\Services\CategoriesService;
use App\Model\Services\LangsService;
use App\Model\Services\ParametersService;
use App\Model\Services\ProductsService;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class ParameterCreateFormControl extends Control
{

	public $modalTitle = 'Vytvoriť parameter produktu';

	public $id;

	/** @var Translator  */
	public $translator;

	/** @var  Orm */
	protected $orm;

	/** @var  ParametersService */
	protected $parametersService;

	/** @var  LangsService */
	protected $langsService;


	public function __construct( $id, Orm $orm, ParametersService $pS, LangsService $lS )
	{
		$this->id = $id;
		$this->orm = $orm;
		$this->parametersService = $pS;
		$this->langsService = $lS;
	}


	public function render()
	{
		$this->template->setFile( __DIR__ . '/parameterCreateForm.latte' );
		$this->template->render();
	}


	public function createComponentForm( string $name ): Form
	{
		$form = new Form();
		//$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.' );

		$langs = $this->langsService->getLangs();

		$names = $form->addContainer('names', 'Názov parametra');
		foreach ( $langs as $lang )
		{
			$names->addText( $lang, 'Názov ' . $lang )
				->setRequired( 'Názov je povinné pole pre každý jazyk.' )
				->setAttribute( 'class', 'form-control' );
		}

		$form->addSelect('type', 'Typ hodnoty', [Parameter::TYPE_STRING => 'Text', Parameter::TYPE_BOOLEAN => 'Ano/Nie'])
			->setPrompt('')
			->setRequired('Vyberte prosím typ hodnoty.');



		$form->addSubmit('sbmt', 'Uložiť')
			->setHtmlAttribute('class', 'btn btn-primary btn-sm');

		$form->onSuccess[] = [$this, 'formSucceeded'];

		return $form;
	}


	public function formSucceeded( Form $form, $values )
	{
		$presenter = $form->getPresenter();
		$values = $presenter->isAjax() ? $form->getHttpData() : $values;

		try
		{
			$this->parametersService->createParameter( (array)$values );
		}
		catch ( \Nextras\Dbal\UniqueConstraintViolationException $e )
		{
			// TODO: Bolo by lepsie zobrazit presne v ktorom jazyku je chyba tj. vyhladat cez query ci v db taky nazov je.
			$form->addError('Parameter s vybraným názvom už existuje. Názov musí byť unikátny.');
			return $form;
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$form->addError('Pri ukladaní došlo k nečakanej chybe. Skúste to prosím ešte raz, alebo kontaktujte administrátora');
			return $form;
		}

		$presenter->flashMessage('Parameter bol vytvorený.', 'success');

		if( $presenter->isAjax() )
		{
			$presenter->showModal = FALSE;
			return;
		}

		$presenter->redirect(':Admin:Products:default');
	}

}

interface IParameterCreateFormControlFactory
{
	public function create($id = NULL): ParameterCreateFormControl;
}