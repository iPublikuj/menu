<?php
/**
 * Menu.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           20.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\Entities\Menus;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Menu\Entities;
use IPub\Menu\Exceptions;

/**
 * Menu container
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Menu implements IMenu
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var Utils\ArrayHash
	 */
	private $items;

	/**
	 * @param string|int $id
	 * @param string $name
	 * @param Entities\Items\IItem[] $items
	 */
	public function __construct($id, string $name, array $items = [])
	{
		$this->id = $id;
		$this->name = $name;

		$this->setItems($items);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setItems(array $items = []) : void
	{
		$this->items = new Utils\ArrayHash;

		foreach ($items as $item) {
			$this->addItem($item);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addItem(Entities\Items\IItem $item) : void
	{
		$this->items->offsetSet($item->getId(), $item);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getItems() : array
	{
		return (array) $this->items;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getItem($id) : Entities\Items\IItem
	{
		if (!$this->hasItem($id)) {
			throw new Exceptions\InvalidArgumentException(sprintf('Menu item "%s" was not found in menu "%s"', $id, (string) $this));
		}

		return $this->items->offsetGet($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasItem($id) : bool
	{
		return $this->items->offsetExists($id);
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		$items = [];

		foreach ($this->getItems() as $item) {
			$items[$item->getId()] = $item;
		}

		return new \ArrayIterator($items);
	}

	/**
	 * @param string|int $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset) : bool
	{
		return $this->items->offsetExists($offset);
	}

	/**
	 * @param string|int $offset
	 *
	 * @return Entities\Items\IItem
	 */
	public function offsetGet($offset)
	{
		return $this->getItem($offset);
	}

	/**
	 * @param string|int $offset
	 * @param Entities\Items\IItem $value
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (!$value instanceof Entities\Items\IItem) {
			throw new Exceptions\InvalidArgumentException(sprintf('Provided value must be instance of IPub\Menu\Entities\IItem, %s provided', get_class($value)));
		}

		$this->addItem($value);
	}

	/**
	 * @param string|int $offset
	 *
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		if ($this->items->offsetExists($offset)) {
			$this->items->offsetUnset($offset);
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->id . ' ' . $this->name;
	}
}
