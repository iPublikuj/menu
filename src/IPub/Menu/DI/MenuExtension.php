<?php
/**
 * MenuExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		20.08.14
 */

namespace IPub\Menu\DI;

use Nette;
use Nette\DI;
use Nette\Utils;
use Nette\PhpGenerator as Code;

use IPub;
use IPub\Menu;

class MenuExtension extends DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		// Menu provider
		$builder->addDefinition($this->prefix('manager'))
			->setClass(Menu\MenuManager::CLASSNAME);

		$filtersManager = $builder->addDefinition($this->prefix('filters.manager'))
			->setClass(Menu\FiltersManager::CLASSNAME);

		// Register menu filters
		$filtersManager->addSetup('register', array('priority', Menu\Filter\PriorityFilter::CLASSNAME, 10));
		$filtersManager->addSetup('register', array('active', Menu\Filter\ActiveFilter::CLASSNAME, 16));
	}

	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();

		// Get menu provider
		$service = $builder->getDefinition($this->prefix('manager'));

		// Check all extensions and search for menu items provider
		foreach ($this->compiler->getExtensions() as $extension) {
			if (!$extension instanceof IMenuItemsProvider) {
				continue;
			}

			// Get menu groups & items from extension
			foreach($extension->getMenuItems() as $menu => $items) {
				foreach($items as $id => $properties) {
					$properties['parentId'] = isset($properties['parent']) ? $properties['parent'] : 0;

					if (!isset($properties['priority'])) {
						$properties['priority'] = 100;
					}

					// Create new menu item
					$item = new Menu\Entities\Item(array_merge($properties, ['id' => $id, 'name' => isset($properties['label']) ? $properties['label'] : $id]));

					$service->addSetup('addItem', array($menu, $item));
				}
			}
		}
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'menu')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new MenuExtension());
		};
	}
}