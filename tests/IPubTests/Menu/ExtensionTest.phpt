<?php
/**
 * Test: IPub\Menu\Extension
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Tests
 * @since          1.0.0
 *
 * @date           18.01.17
 */

declare(strict_types = 1);

namespace IPubTests\Menu;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\Menu;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class ExtensionTest extends Tester\TestCase
{
	public function testFunctional()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('menu.managers.menus') instanceof Menu\Managers\MenuManager);
		Assert::true($dic->getService('menu.managers.filters') instanceof Menu\Managers\FiltersManager);
		Assert::true($dic->getService('menu.filters.priority') instanceof Menu\Filters\Priority\IFilter);
		Assert::true($dic->getService('menu.filters.link') instanceof Menu\Filters\Link\IFilter);
		Assert::true($dic->getService('menu.filters.status') instanceof Menu\Filters\Status\IFilter);
		Assert::true($dic->getService('menu.filters.access') instanceof Menu\Filters\Access\IFilter);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Menu\DI\MenuExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
