<?php

declare(strict_types=1);

namespace App\Router;

use App\Model\Services\LangsService;
use Kdyby\Translation\Translator;
use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Tracy\Debugger;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter( Translator $t, LangsService $lS ): RouteList
	{
		$router = new RouteList();
		$locales = $lS->getLangs();

		$router[] = $adminRouter = new RouteList('Admin');
		$router[] = $frontRouter = new RouteList('Front');

		$adminRouter->addRoute('admin/<presenter>/<action>[/<id>]', 'Default:default');


		$frontRouter->addRoute('[<locale>/]<presenter>[/<action>][/<id>]', [
			'locale' => [
				Route::PATTERN => join('|', $locales),
				Route::VALUE => 'sk'
			],
			'presenter' => [
				Route::PATTERN => 'eshop',
			],
			'action' => [
				Route::VALUE => 'default',
			],
			'id' => [
				Route::PATTERN => '.+'
			]
		]);


		$frontRouter->addRoute('[<locale>/][<presenter>[/<action>]]', [
			'locale' => [
				Route::PATTERN => join('|', $locales),
				Route::VALUE => 'sk',
			],
			'presenter' => [
				Route::PATTERN => 'ponuka|offer',
				Route::VALUE => 'ponuka',
			],
			'action' => [
				Route::VALUE => 'default',
			],
			NULL => [
				Route::FILTER_IN => function ($params) {
					Debugger::barDump($params, 'IN');
					$params['presenter'] = 'Default';
					return $params;
				},
				Route::FILTER_OUT => function ($params) {
					Debugger::barDump( $params, 'OUT' );
					$locale = $params['locale'] ?? 'sk';
					$tr = ['sk' => 'ponuka', 'cs' => 'ponuka', 'en' => 'offer'];
					$params['presenter'] = $tr[$locale];
					return $params;
				}
			]
		]);

		$frontRouter[] = new Route( '[<locale>/]<presenter>/<action>[/<id>]', [
			'locale' => [
				Route::PATTERN => join('|', $locales),
				Route::VALUE => 'sk',
			]
		]);

		return $router;
	}

}
