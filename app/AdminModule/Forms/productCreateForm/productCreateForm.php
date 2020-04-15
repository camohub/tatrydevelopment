<?php

namespace App\Admin\Forms;


use App\Exceptions\DuplicateEntryException;
use App\Model\Orm\Orm;
use App\Model\Orm\Parameter;
use App\Model\Orm\Product;
use App\Model\Services\CategoriesService;
use App\Model\Services\LangsService;
use App\Model\Services\ParametersService;
use App\Model\Services\ProductsService;
use App\Model\Services\UploadsProductsService;
use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;


class ProductCreateFormControl extends Control
{

	public $modalTitle = 'Vytvoriť produkt';

	public $id;

	/** @var Product  */
	public $product = NULL;

	/** @var Translator  */
	public $translator;

	/** @var  Orm */
	protected $orm;

	/** @var  ProductsService */
	protected $productsService;

	/** @var  ParametersService */
	protected $parametersService;

	/** @var  CategoriesService */
	protected $categoriesService;

	/** @var  UploadsProductsService */
	protected $uploadsProductsService;

	/** @var  LangsService */
	protected $langsService;

	protected $parameters;
	protected $parametersToSelect;
	protected $langs;


	public function __construct(
		$id,
		Orm $orm,
		ProductsService $pS,
		ParametersService $parS,
		CategoriesService $cS,
		UploadsProductsService $uPS,
		Translator $t,
		LangsService $lS
	) {
		$this->id = $id;
		$this->orm = $orm;
		$this->productsService = $pS;
		$this->parametersService = $parS;
		$this->categoriesService = $cS;
		$this->uploadsProductsService = $uPS;
		$this->translator = $t;
		$this->langsService = $lS;

		$this->parametersToSelect = $this->parametersService->parametersToSelect();
		$this->parameters = $this->orm->parameters->findAll()->fetchPairs('id');
		$this->langs = $this->langsService->getLangs();
		if( $id ) $this->product = $this->orm->products->getById($id);
	}


	public function render()
	{
		$this->getPresenter()->setReferer(self::class);
		$this->template->setFile( __DIR__ . '/productCreateForm.latte' );
		$this->template->product = $this->product;
		$this->template->langs = $this->langs;
		$this->template->parametersToSelect = $this->parametersToSelect;
		$this->template->parameters = $this->parameters;
		$this->template->render();
	}


	public function createComponentForm( string $name ): Form
	{
		$form = new Form();
		//$form->elementPrototype->addAttributes( array( 'class' => 'ajax' ) );

		$form->addProtection( 'Vypršal čas vyhradený pre odoslanie formulára. Z dôvodu rizika útoku CSRF bola požiadavka na server zamietnutá.' );

		$langs = $this->langs;

		$names = $form->addContainer( 'names', 'Názov produktu' );
		foreach ( $langs as $lang )
		{
			$names->addText( $lang, 'Názov ' . $lang )
				->setRequired( 'Názov je povinné pole pre každý jazyk.' )
				->setHtmlAttribute( 'class', 'form-control' );
		}

		$desc = $form->addContainer( 'desc' );
		foreach ( $langs as $lang )
		{
			$desc->addTextArea( $lang, 'Popis ' . $lang )
				->setRequired( 'Popis je povinná položka.' )
				->setHtmlAttribute( 'class', 'form-control' );
		}

		$form->addMultiSelect('categories', 'Vyberte kategórie', $this->categoriesService->categoriesToSelect())
			->setHtmlAttribute( 'class', 'form-control' )->getOptions();

		$form->addSelect('parent', 'Vyberte nadradený produkt', $this->productsService->productsToSelect())
			->setPrompt('')
			->setHtmlAttribute( 'class', 'form-control' );

		$form->addText('price', 'Cena')
			->addRule($form::FLOAT, 'Cena nieje platné číslo.')
			->addConditionOn($form['parent'], $form::BLANK)
				->setRequired('Cena je povinný údaj.');

		$form->addText('stock', 'Kusov skladom')
			->addRule($form::INTEGER, 'Kusy skladom nie sú platné číslo.')
			->addRule($form::MIN, 'Kusy skladom nesmú obsahovať číslo menšie ako 0', 0);

		////////////////////////////////////////////////////////////////////////////////////////
		///////// PARAMS //////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////
		$form->addMultiSelect('params', 'Filter parametrov', $this->parametersToSelect);

		$parameters = $form->addContainer('parameters', 'Parametre');
		foreach ($this->parametersToSelect as $k => $v)
		{
			foreach ($langs as $l)
			{
				if( $this->parameters[$k]->type == Parameter::TYPE_STRING ) $parameters->addText('parameter_' . $l . '_' . $k, $v . ' ' . $l)->setHtmlAttribute('id', 'parameter_' . $l . '_' . $k);
				elseif( $this->parameters[$k]->type == Parameter::TYPE_BOOLEAN ) $parameters->addCheckbox('parameter_' . $l . '_' . $k, $v . ' ' . $l)->setHtmlAttribute('id', 'parameter_' . $l . '_' . $k);
			}
		}
		///////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////

		$form->addUpload('mainFile', 'Vyberte hlavný obrázok.')
			->addRule( $form::IMAGE, 'Súbor pre hlavný obrázok nieje obrázok.' );

		$form->addMultiUpload( 'files', 'Vybrať obrázky' )
			->addRule( $form::IMAGE, 'Niektorý z vybraných súborov nieje obrázok.' );


		$form->addSubmit('sbmt', 'Uložiť')
			->setHtmlAttribute('class', 'btn btn-primary btn-sm');


		$form->addSubmit('refresh', 'Načítať pridané hodnoty')
			->setHtmlAttribute('class', 'btn btn-success btn-sm')
			->setValidationScope([]);

		if( $this->id ) $this->setDefaults($form);

		$form->onValidate[] = [$this, 'formValidation'];
		$form->onSuccess[] = [$this, 'formSucceeded'];

		return $form;
	}


	public function formValidation( Form $form, $values )
	{
		if( $form['refresh']->isSubmittedBy() ) return $form;

		$presenter = $form->getPresenter();
		$values = $presenter->isAjax() ? $form->getHttpData() : $values;

		if( !empty($values['parent']) && !empty($values['categories'] ) ) $form->addError('Ak je produkt podproduktom iného produktu nemôžete vyberať kategorie. Patrí do kategórií nadradeného produktu');

		if( empty($values['parent']) && empty($values['categories']) ) $form->addError('Vyberte prosím kategóriu alebo nadradený produkt.');

		if( empty($values['params']) )
		{
			$form->addError('Vyplňte prosím parametre produktu.');
		}
		else
		{
			foreach ($values['params'] as $p)
			{
				if( $this->parameters[$p]->type == Parameter::TYPE_STRING && empty($values['parameters']['parameter_sk_' . $p]) ) $form->addError('Vyplňte prosím parametre produktu. Každý vybraný textový parameter musí byť vyplnený aspoň v hlavnom jazyku.');
			}
		}

		if( $form->hasErrors() ) $this->redrawControl();

		return $form;
	}


	public function formSucceeded( Form $form )
	{
		if( $form['refresh']->isSubmittedBy() )
		{
			$this->redrawControl();
			return $form;
		}

		$presenter = $form->getPresenter();
		$values = $presenter->isAjax() ? $form->getHttpData() : $form->getValues(TRUE);

		try
		{
			$this->product ? $this->productsService->updateProduct( $this->product, $values ) : $this->product = $this->productsService->createProduct( $values );
			$uploadResult = $this->uploadsProductsService->saveProductImages( $this->product, $values['mainFile'], $values['files'] );
			if ( $uploadResult['errors'] )
			{
				foreach ( $uploadResult['errors'] as $e ) $presenter->flashDanger( $e );
				$presenter->flashMessage('Produkt bol vytvorený.', 'success');
				$this->redrawControl();
				return $form;
			}
		}
		catch ( DuplicateEntryException $e )
		{
			// TODO: Bolo by lepsie zobrazit presne v ktorom jazyku je chyba tj. vyhladat cez query ci v db taky nazov je.
			$form->addError('Produkt s vybraným názvom už existuje. Názov musí byť unikátny.');
			$this->redrawControl();
			return $form;
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$form->addError('Pri ukladaní došlo k nečakanej chybe. Skúste to prosím ešte raz, alebo kontaktujte administrátora');
			$this->redrawControl();
			return $form;
		}

		$presenter->flashMessage('Produkt bol vytvorený.', 'success');
		$presenter->payload->forceRedirect = TRUE;
		$presenter->redirect(':Admin:Products:default');
	}


	protected function setDefaults(Form $form)
	{
		if( !$this->id ) return $form;

		foreach ($this->product->langs as $pLang)
		{
			$form['names'][$pLang->lang]->setDefaultValue($pLang->name);
			$form['desc'][$pLang->lang]->setDefaultValue($pLang->desc);
		}

		if( $this->product->categories->countStored() )
		{
			$catIds = [];
			foreach ($this->product->categories as $c) $catIds[] = $c->id;
			$form['categories']->setDefaultValue($catIds);
		}

		if( $this->product->parent )
		{
			$form['parent']->setDefaultValue($this->product->parent->id);
		}

		$params = [];
		foreach ($this->product->parameters as $p)
		{
			$params[] = $p->parameter->id;
			$form['parameters']['parameter_' . $p->lang . '_' . $p->parameter->id]->setDefaultValue($p->value);
		}
		$form['params']->setDefaultValue($params);

		$form['price']->setDefaultValue($this->product->price);
		$form['stock']->setDefaultValue($this->product->stock);
	}


	public function handleDeleteImage($id, $productId)
	{
		$this->redrawControl();
		$this->uploadsProductsService->deleteById($id, $productId);
	}

}

interface IProductCreateFormControlFactory
{
	public function create($id = NULL): ProductCreateFormControl;
}