<?php
/**
 * MenuManager.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 * @since          1.0.0
 *
 * @date           24.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\Managers;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Menu\Entities;

/**
 * Menus manager
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class MenuManager extends Nette\Object implements IMenuManager
{
	/**
	 * @var Entities\Menus\IMenu[]|Utils\ArrayHash
	 */
	private $menus = [];

	/**
	 * @var FiltersManager
	 */
	private $filtersManager;

	/**
	 * @param FiltersManager $filtersManager
	 */
	public function __construct(FiltersManager $filtersManager)
	{
		// Register filters manager
		$this->filtersManager = $filtersManager;
		
		$this->menus = new Utils\ArrayHash;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has($id)
	{
		return $this->menus->offsetExists($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($id)
	{
		// Only lower case chars are allowed
		$id = strtolower($id);

		// Check if menu group exists or not...
		if (!$this->has($id)) {
			// ...if not create empty menu group
			$menu = new Entities\Menus\Menu($id, (string) $id);

			// ...and store it in menu provider
			$this->menus->offsetSet($id, $menu);
		}

		return $this->menus->offsetGet($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function addItem($menu, $item, string $name = NULL, array $attributes = [], array $data = [])
	{
		if (!$menu instanceof Entities\Menus\IMenu) {
			$menu = $this->get($menu);
		}

		if (!$item instanceof Entities\Items\IItem) {
			$id = $item;
			$name = $name ? $name : $item;

			$item = new Entities\Items\Item($menu, $id, $name, $attributes, $data);
		}

		$menu->addItem($item);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTree($menu, array $parameters = [])
	{
		if (!$menu instanceof Entities\Menus\IMenu) {
			$menu = $this->get($menu);
		}

		/** @var \ArrayIterator $iterator */
		$iterator = $menu->getIterator();

		// Apply widgets filters
		foreach ($this->filtersManager as $filter) {
			$iterator = $filter->create($iterator, $parameters);
		}

		$rootItem = new Entities\Items\Item($menu, Entities\Items\IItem::ROOT_ID, Entities\Items\IItem::ROOT_NAME);

		$iterator->offsetSet(Entities\Items\IItem::ROOT_ID, $rootItem);

		$items = [Entities\Items\IItem::ROOT_ID => new Entities\Nodes\Node($rootItem)];

		foreach ($iterator as $item) {
			$id = $item->getId();
			$pid = $item->getParentId() ? $item->getParentId() : Entities\Items\IItem::ROOT_ID;

			if ($id === Entities\Items\IItem::ROOT_ID) {
				continue;
			}

			if (!isset($items[$id])) {
				$items[$id] = new Entities\Nodes\Node($item);
			}

			if ($pid && $iterator->offsetExists($pid)) {
				if (!isset($items[$pid])) {
					$items[$pid] = new Entities\Nodes\Node($iterator->offsetGet($pid));
				}

				$items[$pid]->addChild($items[$id]);
			}
		}

		return $items[isset($parameters['root'], $items[$parameters['root']]) ? $parameters['root'] : Entities\Items\IItem::ROOT_ID];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator() : \ArrayIterator
	{
		return $this->menus->getIterator();
	}

	/**
	 * @param string $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset) : bool
	{
		return $this->menus->offsetExists($offset);
	}

	/**
	 * @param string $offset
	 *
	 * @return Entities\Menus\IMenu
	 */
	public function offsetGet($offset) : Entities\Menus\IMenu
	{
		return $this->menus->offsetGet($offset);
	}

	/**
	 * @param string $offset
	 * @param Entities\Menus\IMenu $value
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->menus->offsetSet($offset, $value);
	}

	/**
	 * @param string $offset
	 *
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		$this->menus->offsetUnset($offset);
	}
}
