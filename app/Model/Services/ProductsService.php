<?php


namespace App\Model\Services;


use App;
use App\Exceptions\CategoryContainsProductsException;
use App\Model\Entity;
use App\Model\Orm\Category;
use App\Model\Orm\CategoryLang;
use App\Model\Orm\Orm;
use App\Model\Orm\Parameter;
use App\Model\Orm\Product;
use App\Model\Orm\ProductLang;
use App\Model\Orm\ProductParameter;
use App\Model\Repositories\ArticlesRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Kdyby\Doctrine\ResultSet;
use Kdyby\Translation\Translator;
use Nette;
use Nette\Utils\Strings;
use App\Model\Repositories\CategoriesArticlesRepository;
use App\Model\Repositories\ArticlesCategoriesArticlesRepository;
use Nextras\Orm\Collection\ICollection;
use Tracy\Debugger;


class ProductsService
{

	/** @var Orm */
	protected $orm;

	/** @var LangsService */
	protected $langsService;

	/** @var Translator */
	protected $translator;


	/**
	 * ProductsService constructor.
	 * @param Orm $orm
	 * @param LangsService $lS
	 * @param Translator $tr
	 */
	public function __construct( Orm $orm, LangsService $lS, Translator $tr )
	{
		$this->orm = $orm;
		$this->langsService = $lS;
		$this->translator = $tr;
	}


	/**
	 * @param $category
	 * @return mixed
	 */
	public function switchVisibility( Category $category )
	{
		$newStatus = $category->status == Category::STATUS_PUBLISHED ? Category::STATUS_UNPUBLISHED : Category::STATUS_PUBLISHED;
		$category->status = $newStatus;
		$this->orm->categories->persistAndFlush($category);

		$this->cleanCache();

		return $category;
	}


	/**
	 * @desc Find ids of category and nested categories.
	 * @param Entity\CategoryArticle $category
	 * @param $ids array
	 * @return array
	 */
	public function findCategoryTreeIds( Entity\CategoryArticle $category, array $ids = [] )
	{
		$ids[] = $category->getId();

		if ( $children = $this->orm->categories->findBy(['parent' => $category->getId()]))
		{
			foreach ( $children as $child )
			{
				$ids = $this->findCategoryTreeIds( $child, $ids );
			}
		}

		return $ids;
	}


	/**
	 * @return array
	 */
	public function productsToSelect()
	{
		return $this->orm->products->findAdminProducts()->fetchPairs('id', 'name');
	}


	/**
	 * @param array $params
	 * @return Product
	 * @throws App\Exceptions\DuplicateEntryException
	 * @throws \Exception
	 */
	public function createProduct( array $params )
	{
		try
		{
			$product = new Product();

			$product->price = $params['price'];
			$product->stock = $params['stock'];

			if( $params['parent'] ) $product->parent = $this->orm->products->getById($params['parent']);

			$this->setProductCategories($product, $this->orm->categories->findById($params['categories']));
			$this->setProductPrameters($product, $params);

			foreach ( $this->langsService->getLangs() as $lang )
			{
				$product_lang = new ProductLang();
				$product_lang->name = $params['names'][$lang];
				$product_lang->desc = $params['desc'][$lang];
				$product_lang->lang = $lang;
				$product_lang->product = $product;
				$this->orm->productsLangs->persist($product_lang);
				$product->langs->add($product_lang);
			}

			$this->orm->products->persistAndFlush( $product );
		}
		catch ( \Nextras\Dbal\UniqueConstraintViolationException $e )
		{
			throw new App\Exceptions\DuplicateEntryException();
		}

		return $product;
	}


	public function updateProduct( Product $product, array $params )
	{
		try
		{
			$product->price = $params['price'];
			$product->stock = $params['stock'];

			if( $params['parent'] ) $product->parent = $this->orm->products->getById($params['parent']);
			else $product->parent = NULL;

			$this->setProductCategories($product, $this->orm->categories->findById($params['categories']));
			$this->setProductPrameters($product, $params);

			foreach ( $this->langsService->getLangs() as $lang )
			{
				$product_lang = $product->langs->get()->getBy(['lang' => $lang]) ?: new ProductLang();
				$product_lang->name = $params['names'][$lang];
				$product_lang->desc = $params['desc'][$lang];
				$product_lang->lang = $lang;
				$product_lang->product = $product;  // If it is a new lang
				$this->orm->productsLangs->persist($product_lang);
				$product->langs->add($product_lang);  // If it is a new lang
			}

			$this->orm->products->persistAndFlush( $product );
		}
		catch ( \Nextras\Dbal\UniqueConstraintViolationException $e )
		{
			throw new App\Exceptions\DuplicateEntryException();
		}

		return $product;
	}


	protected function setProductCategories(Product &$product, ICollection $categories)
	{
		foreach ($product->categories as $c) $product->categories->remove($c);

		foreach ($categories as $c)
		{
			$product->categories->add($c);
			if( $c->adminCategories->countStored() ) $this->setProductCategories($product, $c->adminCategories);
		}
	}


	protected function setProductPrameters(Product &$product, array $values)
	{
		foreach ($product->parameters as $pp)
		{
			$product->parameters->remove($pp);
			$this->orm->productsParameters->remove($pp);
			$this->orm->productsParameters->persist($pp);
		}

		$parameters = $this->orm->parameters->findById($values['params'])->fetchPairs('id');

		foreach ($parameters as $pId => $p)
		{
			foreach ($this->langsService->getLangs() as $l)
			{
				if( $p->type == Parameter::TYPE_BOOLEAN )
				{
					$productParam = new ProductParameter();
					$productParam->lang = $l;
					$productParam->value = $values['parameters']['parameter_' . $l . '_' . $pId] ? 1 : 0;  // "0" is ok (bool) makes FALSE
					$productParam->product = $product;
					$productParam->parameter = $p;
					$this->orm->productsParameters->persist($productParam);
					$product->parameters->add($productParam);
				}
				elseif( $p->type == Parameter::TYPE_STRING && !empty($values['parameters']['parameter_' . $l . '_' . $pId]) )
				{
					$productParam = new ProductParameter();
					$productParam->lang = $l;
					$productParam->value = $values['parameters']['parameter_' . $l . '_' . $pId];
					$productParam->product = $product;
					$productParam->parameter = $p;
					$this->orm->productsParameters->persist($productParam);
					$product->parameters->add($productParam);
				}
			}
		}
	}

	/**
	 * @param $id
	 * @param array $names
	 * @return null|object
	 * @throws App\Exceptions\DuplicateEntryException
	 */
	public function updateName( $id, array $names )
	{
		// No need transaction...
		try
		{
			$category = $this->orm->categories->getById( $id );
			foreach ( $category->langs as $lang )
			{
				$lang->name = $names[$lang->lang];
				$this->orm->categoriesLangs->persistAndFlush( $lang );
			}
		}
		catch ( \Nextras\Dbal\UniqueConstraintViolationException $e )
		{
			throw new App\Exceptions\DuplicateEntryException( 'Kategória s rovnakým názvom už exituje. Názov musí byť v každnom jazyku unikátny.' );
		}

		return $category;

	}


	public function updateStatus( $id, $value )
	{
		$product = $this->orm->products->getById($id);
		$product->status = (int)$value;
		$this->orm->products->persistAndFlush($product);
		Debugger::log($product->status);

		return $product;
	}


	/**
	 * @param array $arr
	 * @return bool
	 */
	public function updatePriority( array $arr )
	{
		$pairs = $this->orm->categories->findAll()->fetchPairs('id');
		$i = 1;
		foreach ( $arr as $key => $val )
		{
			// if the array is large it would be better to update only changed items but the problem is priority
			$category = $pairs[(int)$key];
			$category->parent = $val == 0 ? NULL : (int)$val;
			$category->priority = $i;

			$this->orm->categories->persistAndFlush($category);

			$i++;
		}

		$this->cleanCache();

		return true;
	}


	/**
	 * @param Category $category
	 * @return array
	 * @throws NoCategoryException
	 */
	public function delete( Category $category )
	{
		if( ! $category ) throw new NoCategoryException( 'Category not found.' );

		$result = $this->canDelete( $category );

		$names = [];
		foreach ( $result['items'] as $item )
		{
			$names[] = $item->name;
			$this->orm->categories->remove( $item );
		}

		$this->orm->categories->flush();

		$this->cleanCache();

		return $names;

	}


//////Protected/Private///////////////////////////////////////////////////////

	/**
	 * @param Category $category
	 * @param null $result
	 * @return array|null
	 * @throws CategoryContainsProductsException
	 */
	protected function canDelete( Category $category, $result = NULL )
	{
		$result = $result ?: [ 'items' => [] ];

		if ( $category->products->countStored() ) throw new CategoryContainsProductsException('Category ' . $category->name . ' can not be deleted. Category contains one or more products.', $category->name);

		foreach ( $category->categories as $child )
		{
			$result = $this->canDelete( $child, $result );
		}

		$result['items'][] = $category;

		return $result;

	}


}