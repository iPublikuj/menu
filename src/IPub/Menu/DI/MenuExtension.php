<?php
/**
 * MenuExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           20.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\DI;

use Nette;
use Nette\DI;
use Nette\Utils;
use Nette\PhpGenerator as Code;

use IPub;
use IPub\Menu;
use IPub\Menu\Entities;
use IPub\Menu\Filters;
use IPub\Menu\Managers;

/**
 * Menu extension container
 *
 * @package        iPublikuj:Menu!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class MenuExtension extends DI\CompilerExtension
{
	// Define tag string for menu filters
	const TAG_MENU_FILTER = 'ipub.menu.filter';

	/**
	 * @return void
	 */
	public function loadConfiguration()
	{
		// Get container builder
		$builder = $this->getContainerBuilder();

		/**
		 * Menu services
		 */

		$builder->addDefinition($this->prefix('managers.menus'))
			->setClass(Managers\MenuManager::class);

		/**
		 * Menu filters
		 */

		$builder->addDefinition($this->prefix('managers.filters'))
			->setClass(Managers\FiltersManager::class)
			->addTag('cms.menu');

		// Menu priority filter
		$builder->addDefinition('filters.priority')
			->setClass(Filters\Priority\Filter::class)
			->setImplement(Filters\Priority\IFilter::class)
			->setInject(TRUE)
			->setTags(['cms.menu', 'cms.menu.filter'])
			->addTag(self::TAG_MENU_FILTER, 10);

		// Menu link filter
		$builder->addDefinition('filters.link')
			->setClass(Filters\Link\Filter::class)
			->setImplement(Filters\Link\IFilter::class)
			->setInject(TRUE)
			->setTags(['cms.menu', 'cms.menu.filter'])
			->addTag(self::TAG_MENU_FILTER, 15);

		// Menu status filter
		$builder->addDefinition('filters.status')
			->setClass(Filters\Status\Filter::class)
			->setImplement(Filters\Status\IFilter::class)
			->setInject(TRUE)
			->setTags(['cms.menu', 'cms.menu.filter'])
			->addTag(self::TAG_MENU_FILTER, 20);

		// Menu access filter
		$builder->addDefinition('filters.access')
			->setClass(Filters\Access\Filter::class)
			->setImplement(Filters\Access\IFilter::class)
			->setInject(TRUE)
			->setTags(['cms.menu', 'cms.menu.filter'])
			->addTag(self::TAG_MENU_FILTER, 5);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		parent::beforeCompile();

		// Get container builder
		$builder = $this->getContainerBuilder();

		// Get menu provider
		$menuManager = $builder->getDefinition($this->prefix('managers.menus'));

		// Check all extensions and search for menu items provider
		foreach ($this->compiler->getExtensions() as $extension) {
			if (!$extension instanceof IMenuItemsProvider) {
				continue;
			}

			// Get menu groups & items from extension
			foreach ($extension->getMenuItems() as $menu => $items) {
				foreach ($items as $id => $properties) {
					if (!isset($properties['name'])) {
						continue;
					}

					$properties = array_merge_recursive([
						'attributes' => [],
						'data'       => []
					], $properties);

					if (!isset($properties['attributes']['parentId'])) {
						$properties['attributes']['parentId'] = NULL;
					}

					if (isset($properties['parent'])) {
						$properties['attributes']['parentId'] = $properties['parent'];
						unset($properties['parent']);
					}

					if (!isset($properties['attributes']['priority'])) {
						$properties['attributes']['priority'] = 100;
					}

					$menuManager->addSetup('addItem', [$menu, $id, $properties['name'], $properties['attributes'], $properties['data']]);
				}
			}
		}

		// Get widgets filters manager
		$filtersManager = $builder->getDefinition($this->prefix('managers.filters'));

		// Get all registered widgets decorators
		foreach (array_keys($builder->findByTag(self::TAG_MENU_FILTER)) as $serviceName) {
			$priority = $builder->getDefinition($serviceName)->getTag(self::TAG_MENU_FILTER);
			$priority = is_integer($priority) ? $priority : 999;

			// Register filter to manager
			$filtersManager->addSetup('register', ['@' . $serviceName, $serviceName, $priority]);
		}
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'menu')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new MenuExtension());
		};
	}
}
