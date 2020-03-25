<?php


namespace App\AdminModule\Presenters;


use App;
use App\Admin\Components\IAdminEshopMenuControlFactory;
use App\AdminModule\Forms\CategoriesEditFormFactory;
use App\AdminModule\Forms\CategoryFormFactory;
use Tracy\Debugger;


class CategoriesPresenter extends BaseAdminPresenter
{

	/** @var  App\Model\Services\CategoriesService @inject */
	public $categoriesService;

	/** @var  App\Admin\Forms\ICategoryNameFormControlFactory @inject */
	public $categoryNameFormFactory;

	/** @var  App\Admin\Forms\ICategoryCreateFormControlFactory @inject */
	public $categoryCreateFormFactory;

	/** @var  IAdminEshopMenuControlFactory @inject */
	public $adminEshopCategoriesFactory;

	public $id = NULL;


	public function startup()
	{
		parent::startup();
		$this->checkPermission('category', 'edit', 'Nemáte oprávnenie editovať kategórie.');
	}


	public function actionDefault( $id = NULL, $delete = FALSE, $createForm = FALSE )
	{
		$this->template->categories = $this->orm->categories->findAdminTopCategories();

		if( $id && ! $delete )
		{
			$this->id = (int)$id;
			$this->showModal = TRUE;
			$this['modal']->addContent($this['editCategoryNameForm']);
		}
		elseif( $createForm )
		{
			$this->showModal = TRUE;
			$this['modal']->addContent($this['categoryCreateForm']);
		}
	}


	/**
	 * @secured
	 */
	public function handleCategoriesPriority()
	{
		try
		{
			$this->categoriesService->updatePriority( $_GET['categoryItems'] );
			$this->flashSuccess('Poradie položiek bolo upravené.');
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$this->flashMessage('Pri ukladaní údajov došlo k chybe.', 'error');
		}

		if ( $this->isAjax() )
		{
			$this->redrawControl( 'flash' );
			return;
		}

		$this->redirect( 'this' );

	}


	/**
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleChangeCategoryVisibility( $id )
	{
		$category = $this->categoryArticleRepository->find( $id );

		try
		{
			$this->categoriesArticlesService->switchVisibility( $category );
			// Dynamic snippet redraw needs to set only one item to template.
			$this->articlesCategories = $this->categoryArticleRepository->findBy( ['id' => $id] );
			$this->flashMessage( 'Viditeľnosť položky bola upravená.', 'success' );
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$this->flashMessage( 'Pri ukladaní údajov došlo k chybe.', 'error' );
		}

		if( $this->isAjax() )
		{
			$this->redrawControl( 'sortableList' );
			$this->redrawControl( 'sortableListScript' );
			$this->redrawControl( 'flash' );
			return;
		}

		$this->redirect( ':Admin:Menu:default' );

	}


	/**
	 * @param $id
	 * @throws App\Exceptions\AccessDeniedException
	 */
	public function handleDeleteCategory( $id )
	{
		$this->showModal = FALSE;
		$category = $this->orm->categories->getById( $id );

		try
		{
			$names = $result = $this->categoriesService->delete( $category );
			$this->template->categories = $this->orm->categories->findAdminTopCategories();
			$this->flashMessage( 'Categória ' . join( ', ', $names ) . ' bola zmazaná.' );
		}
		catch ( App\Exceptions\CategoryContainsProductsException $e )
		{
			$this->flashMessage( $e->getMessage(), 'error' );
			return;
		}
		catch ( \Exception $e )
		{
			Debugger::log($e);
			$this->flashMessage( 'Pri ukladaní údajov došlo k chybe.', 'error' );
			return;
		}

		if ( $this->isAjax() )  // Not used for now.
		{
			$this->redrawControl( 'sortableList' );
			return;
		}

		$this->redirect( ':Admin:Categories:default' );

	}


////// Controls ////////////////////////////////////////////////////////////////////////

	public function createComponentAdminEshopCategories()
	{
		return $this->adminEshopCategoriesFactory->create();
	}


	public function createComponentEditCategoryNameForm()
	{
		return $this->categoryNameFormFactory->create( $this->id );
	}


	public function createComponentCategoryCreateForm()
	{
		return $this->categoryCreateFormFactory->create();
	}

}
