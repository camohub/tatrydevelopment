<?php

namespace App\Model\Services;


use App;
use App\Model\Orm\Orm;
use App\Model\Orm\Product;
use App\Model\Orm\ProductImage;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette;
use App\Model\Repositories\UploadsProductsRepository;
use App\Model\Repositories\ModulesRepository;
use Tracy\Debugger;


class UploadsProductsService
{

	/** @var Orm */
	public $orm;

	/** @var  string */
	protected $www_dir;

	const PATH = '/uploads/eshop/products';


	public function __construct( $www_dir, Orm $orm )
	{
		$this->orm = $orm;
		$this->www_dir = $www_dir;
		$this->path = $this->www_dir . self::PATH;
	}


	public function saveProductImages( Product $product, $mainFile, $files )
	{
		$path = $this->path . '/' .$product->id;

		try
		{
			Nette\Utils\FileSystem::createDir( $path );
			Nette\Utils\FileSystem::createDir( $path . '/thumbnails' );
			Nette\Utils\FileSystem::createDir( $path . '/mediums' );
		}
		catch( \Exception $e )
		{
			Debugger::log($e);
			throw new App\Exceptions\FileUploadCreateDirectoryException($e->getMessage());
		}

		$hasMain = FALSE;
		$result = ['errors' => [], 'saved_items' => []];

		if( $mainFile->hasFile() && $mainFile->isOk() )
		{
			$hasMain = TRUE;
			array_unshift($files, $mainFile);
			if( $prevMainImage = $product->mainImage )
			{
				$prevMainImage->main = NULL;
				$this->orm->productsImages->persistAndFlush($prevMainImage);
			}
		}

		$i = 0;
		foreach ( $files as $file )
		{
			if ( $file->isOk() )
			{
				$i++;
				$name = $file->getName();
				$sName = $file->getSanitizedName();
				$tmpName = $file->getTemporaryFile();

				$spl = new \SplFileInfo( $sName );
				$sName = $spl->getBasename( '.' . $spl->getExtension() ) . '-' . microtime(TRUE) . '.' . $spl->getExtension();

				try
				{
					if($this->orm->productsImages->getBy(['file' => $sName]))
					{
						$result['errors'][] = 'Súbor s názvom ' . $name . ' už v databáze existuje. Názov musí byť unikátny.';
						continue;
					}

					$productImage = new ProductImage();
					$productImage->file = $sName;
					if( $hasMain && $i === 1 ) $productImage->main = 1;
					$productImage->product = $product;
					$this->orm->productsImages->persistAndFlush($productImage, FALSE);

					$img = Nette\Utils\Image::fromFile( $tmpName );
					$x = $img->width;
					$y = $img->height;

					if ( $x > 1200 || $y > 1000 )
					{
						$img->resize( 1200, 1000 );  // Keeps ratio => one of the sides can be shorter, but none will be longer
					}
					$img->save( $path . '/' . $sName );

					if ( $x > 400 )
					{
						$img->resize( 400, NULL );  // Width will be 400px and height keeps ratio
					}
					$img->save( $path . '/mediums/' . $sName );

					if ( $x > 150 )
					{
						$img->resize( 150, NULL );  // Width will be 150px and height keeps ratio
					}
					$img->save( $path . '/thumbnails/' . $sName );

					$result['saved_items'][] = $name;
				}
				catch ( \Exception $e )
				{
					Debugger::log($e);
					@$this->unlink( $path, $sName );  // If something is saved, delete it.
					$result['errors'][] = 'Pri ukladaní súboru ' . $name . ' došlo k chybe. Súbor nebol uložený.';
				}
			}
			else
			{
				$result['errors'][] = 'Pri ukladaní súboru došlo k chybe.';
			}
		}

		return $result;
	}


	public function deleteById($id, $productId)
	{
		$image = $this->orm->productsImages->getBy(['id' => $id, 'product' => $productId]);
		$this->unlink($this->path . '/' . $productId, $image->file);
		$this->orm->productsImages->removeAndFlush($image);
	}

///// PROTECTED /////////////////////////////////////////////////////////////////////////////////////

	protected function unlink( $path, $name )
	{
		@unlink( $path . '/' . $name );
		@unlink( $path . '/mediums/' . $name );
		@unlink( $path . '/thumbnails/' . $name );
	}


}
