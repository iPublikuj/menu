<?php
/**
 * Test: IPub\Menu\Menu
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
use Nette\Application;
use Nette\Application\UI;

use Tester;
use Tester\Assert;

use IPub;
use IPub\Menu;
use IPub\Menu\Entities;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require __DIR__ . DS . 'libs' . DS . 'RouterFactory.php';

class MenuTest extends Tester\TestCase
{
	/**
	 * @var Application\IPresenterFactory
	 */
	private $presenterFactory;

	/**
	 * @var Application\Application
	 */
	private $application;

	/**
	 * @var Menu\Managers\MenuManager
	 */
	private $menuManager;

	/**
	 * {@inheritdoc}
	 */
	public function setUp()
	{
		parent::setUp();

		$dic = $this->createContainer();

		// Get presenter factory from container
		$this->presenterFactory = $dic->getByType(Application\IPresenterFactory::class);

		$this->application = $dic->getByType(Application\Application::class);

		// Get extension services
		$this->menuManager = $dic->getService('menu.managers.menus');

		$this->menuManager->addItem('test-menu', 'item-one', 'Item 1', [
			'label' => 'Item 1 label',
			'target'   => ':Test:itemOne',
			'active'   => [
				':Test:itemOne',
				':Test:itemTwo',
			],
			'access'   => [
				'user@guest'
			],
		]);

		$this->menuManager->addItem('test-menu', 'item-two', 'Item 2', [
			'label' => 'Item 2 label',
			'target'   => ':Test:itemTwo',
			'active'   => [
				':Test:itemTwo',
			],
			'access'   => [
				'user@guest'
			],
		]);

		$this->menuManager->addItem('test-menu', 'item-three', 'Sub Item 1', [
			'parentId' => 'item-one',
			'label' => 'Sub Item 1 label',
			'target'   => ':Test:itemThree',
			'active'   => [
				':Test:itemOne',
				':Test:itemThree',
			],
			'access'   => [
				'user@guest'
			],
		]);
	}

	public function testMenuContainer()
	{
		/** @var Entities\Menus\IMenu $menuContainer */
		$menuContainer = $this->menuManager->get('test-menu');

		Assert::true($menuContainer instanceof Entities\Menus\IMenu);
		Assert::same('test-menu', $menuContainer->getId());
		Assert::same('test-menu', $menuContainer->getName());
		Assert::count(3, $menuContainer->getItems());
	}

	public function testMenuTree()
	{
		$_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'] = 'ipulikuj.eu';

		// Create GET request
		$request = new Application\Request('Test', 'GET', ['action' => 'default']);
		// & fire presenter
		$this->application->processRequest($request);

		$nodes = $this->menuManager->getTree('test-menu');

		Assert::count(4, $nodes);

		/** @var Entities\Nodes\Node $rootNode */
		$rootNode = reset($nodes);

		Assert::true($rootNode->getItem() instanceof Entities\Items\IItem);
		Assert::same(Entities\Items\IItem::ROOT_ID, $rootNode->getId());
		Assert::same(Entities\Items\IItem::ROOT_NAME, $rootNode->getName());

		Assert::count(2, $rootNode->getChildren());
	}

	/**
	 * @return Application\IPresenter
	 */
	private function createPresenter() : Application\IPresenter
	{
		// Create test presenter
		$presenter = $this->presenterFactory->createPresenter('Test');
		// Disable auto canonicalize to prevent redirection
		$presenter->autoCanonicalize = FALSE;

		return $presenter;
	}

	/**
	 * @return Nette\DI\Container
	 */
	private function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		Menu\DI\MenuExtension::register($config);

		$version = getenv('NETTE');

		if (!$version || $version == 'default') {
			$config->addConfig(__DIR__ . DS . 'files' . DS . 'presenters.neon');

		} else {
			$config->addConfig(__DIR__ . DS . 'files' . DS . 'presenters_2.3.neon');
		}

		return $config->createContainer();
	}
}

class TestPresenter extends UI\Presenter
{
	use Menu\TMenu;

	public function renderDefault()
	{
		// Set template for component testing
		$this->template->setFile(__DIR__ . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'default.latte');
	}
}

\run(new MenuTest());
