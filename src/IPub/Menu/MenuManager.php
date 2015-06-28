<?php
/**
 * MenuManager.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	common
 * @since		5.0
 *
 * @date		24.08.14
 */

namespace IPub\Menu;

use Nette;

use IPub\Menu\Filter;

class MenuManager extends Nette\Object implements \IteratorAggregate, \ArrayAccess
{
	const CLASSNAME = __CLASS__;

	/**
	 * @var Entities\IMenu[]
	 */
	protected $menus = [];

	/**
	 * @var FiltersManager
	 */
	protected $filtersManager;

	/**
	 * @param FiltersManager $filtersManager
	 */
	public function __construct(FiltersManager $filtersManager)
	{
		// Register filters manager
		$this->filtersManager = $filtersManager;
	}

	/**
	 * Checks whether a menu is registered
	 */
	public function has($id)
	{
		return isset($this->menus[$id]);
	}

	/**
	 * Gets a menu
	 *
	 * @param  string $id
	 *
	 * @return Entities\IMenu
	 */
	public function get($id)
	{
		// Only lower case chars are allowed
		$id = strtolower($id);

		// Check if menu group exists or not...
		if (!$this->has($id)) {
			// ...if not create empty menu group
			$menu = (new Entities\Menu)
				->setId($id)
				->setName($id);

			// ...and store it in menu provider
			$this->menus[$id] = $menu;
		}

		return $this->menus[$id];
	}

	/**
	 * Helper method for DI connection
	 *
	 * @param string|Entities\IMenu $menu
	 * @param Entities\IItem $item
	 *
	 * @return $this
	 */
	public function addItem($menu, Entities\IItem $item)
	{
		if ($menu instanceof Entities\IMenu) {

		} else {
			$menu = $this->get($menu);
		}

		$menu->addItem($item);

		return $this;
	}

	/**
	 * @return FiltersManager
	 */
	public function getFilterManager()
	{
		return $this->filtersManager;
	}

	/**
	 * Retrieves menu item tree
	 *
	 * @param  string|Entities\IMenu $menu
	 * @param  array $parameters
	 *
	 * @return Node
	 */
	public function getTree($menu, array $parameters = [])
	{
		if (!$menu instanceof Entities\IMenu) {
			$menu = $this->get($menu);
		}

		$iterator = $menu->getIterator();

		foreach ($this->filtersManager as $priority=>$filters) {
			foreach ($filters as $class) {
				$iterator = new $class($iterator, $parameters);
			}
		}

		$items = [new Node(0)];

		foreach ($iterator as $item) {
			$id		= $item->getId();
			$pid	= $item->getParentId();

			if (!isset($items[$id])) {
				$items[$id] = new Node($id);
			}

			$items[$id]->setItem($item);

			if (!isset($items[$pid])) {
				$items[$pid] = new Node($pid);
			}

			$items[$pid]->add($items[$id]);
		}

		return $items[isset($parameters['root'], $items[$parameters['root']]) ? $parameters['root'] : 0];
	}

	/**
	 * Implements the IteratorAggregate.
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->menus);
	}

	/**
	 * Whether an application parameter or an object exists
	 *
	 * @param  string $offset
	 * @return mixed
	 */
	public function offsetExists($offset)
	{
		return isset($this->menus[$offset]);
	}

	/**
	 * Gets an application parameter or an object
	 *
	 * @param  string $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->menus[$offset];
	}

	/**
	 * Sets an application parameter or an object
	 *
	 * @param  string $offset
	 * @param  mixed  $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->menus[$offset] = $value;
	}

	/**
	 * Unsets an application parameter or an object
	 *
	 * @param  string $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->menus[$offset]);
	}
}