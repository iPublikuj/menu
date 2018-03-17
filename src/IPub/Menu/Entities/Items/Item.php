<?php
/**
 * Item.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 * @since          1.0.0
 *
 * @date           20.08.14
 */

declare(strict_types = 1);

namespace IPub\Menu\Entities\Items;

use Nette;
use Nette\Utils;

use IPub;
use IPub\Menu\Entities;
use IPub\Menu\Exceptions;

/**
 * Menu item entity
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @property string|int $id
 * @property Entities\Menus\IMenu $menu
 * @property string|int $parentId
 * @property string $name
 * @property string|NULL $label
 * @property string|array $target
 * @property int $priority
 * @property Utils\ArrayHash $attributes
 * @property Utils\ArrayHash $data
 */
final class Item implements IItem
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var string|int
	 */
	private $id;

	/**
	 * @var Entities\Menus\IMenu
	 */
	private $menu;

	/**
	 * @var string|int
	 */
	private $parentId;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string|NULL
	 */
	private $label = NULL;

	/**
	 * @var string|array
	 */
	private $target;

	/**
	 * @var int
	 */
	private $priority = 999;

	/**
	 * @var Utils\ArrayHash
	 */
	private $attributes;

	/**
	 * @var Utils\ArrayHash
	 */
	private $data;

	/**
	 * @param Entities\Menus\IMenu $menu
	 * @param string|int $id
	 * @param string $name
	 * @param array $attributes
	 * @param array $data
	 */
	public function __construct(Entities\Menus\IMenu $menu, $id, string $name, array $attributes = [], array $data = [])
	{
		if ($id === self::ROOT_ID && $name !== self::ROOT_NAME) {
			throw new Exceptions\InvalidArgumentException(sprintf('Item ID "%s" is reserved for internal use only', self::ROOT_ID));
		}

		$this->menu = $menu;

		$this->id = $id;
		$this->name = $name;

		$this->attributes = $this->data = new Utils\ArrayHash;

		foreach ($attributes as $attribute => $value) {
			try {
				$this->__set($attribute, $value);

			} catch (Nette\MemberAccessException $ex) {
				$this->setAttribute($attribute, $value);
			}
		}

		$this->setData($data);

		$menu->addItem($this);
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
	public function getMenu() : Entities\Menus\IMenu
	{
		return $this->menu;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParentId($parentId) : void
	{
		$this->parentId = $parentId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParentId()
	{
		return $this->parentId;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParent() : ?IItem
	{
		return $this->menu->hasItem($this->parentId) ? $this->menu->getItem($this->parentId) : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setName(string $name) : void
	{
		$this->name = $name;
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
	public function setLabel(string $label) : void
	{
		$this->label = $label;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLabel() : string
	{
		return $this->label ? $this->label : $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setTarget($target) : void
	{
		$this->target = $target;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasAbsoluteTarget() : bool
	{
		return $this->getTarget() && is_string($this->getTarget()) && preg_match('/^(http|https)\:\/\//', $this->getTarget()) === 1;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPriority(int $priority) : void
	{
		$this->priority = $priority;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority() : int
	{
		return $this->priority;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setAttributes(array $attributes) : void
	{
		$this->attributes = new Utils\ArrayHash;

		foreach ($attributes as $name => $attribute) {
			$this->setAttribute($name, $attribute);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function setAttribute(string $name, $value) : void
	{
		$this->attributes->offsetSet($name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttributes() : Utils\ArrayHash
	{
		return $this->attributes;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAttribute(string $name, $default = NULL)
	{
		return $this->attributes->offsetExists($name) ? $this->attributes->offsetGet($name) : $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isActive() : bool
	{
		return $this->getAttribute('isActive', FALSE);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isVisible() : bool
	{
		return $this->getAttribute('isVisible', FALSE);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLink() : ?string
	{
		return $this->hasAbsoluteTarget() ? $this->getTarget() : $this->getAttribute('link', NULL);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasAccess(bool $default = TRUE) : bool
	{
		return $this->getAttribute('isAllowed', $default);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasData(string $name) : bool
	{
		return $this->data->offsetExists($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData(string $name = NULL)
	{
		if ($name !== NULL && !$this->hasData($name)) {
			throw new Exceptions\InvalidArgumentException(sprintf('Undefined data "%s" in "%s" menu item.', $name, $this->name));
		}

		return $name === NULL ? $this->data : $this->data->offsetGet($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setData(array $data) : void
	{
		$this->data = new Utils\ArrayHash;

		foreach ($data as $name => $value) {
			$this->addData($name, $value);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function addData(string $name, $value) : void
	{
		$this->data->offsetSet($name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasIcon() : bool
	{
		return $this->hasData('icon');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIcon() : ?string
	{
		return $this->hasIcon() ? $this->getData('icon') : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setIcon(string $icon) : void
	{
		$this->addData('icon', $icon);
	}

	/**
	 * {@inheritdoc}
	 */
	public function hasCounter() : bool
	{
		return $this->hasData('counter');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCounter() : ?int
	{
		return $this->hasCounter() ? $this->getData('counter') : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setCounter(int $count) : void
	{
		$this->addData('counter', $count);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->id . ' ' . $this->name;
	}
}
