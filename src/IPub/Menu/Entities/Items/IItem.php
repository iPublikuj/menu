<?php
/**
 * IItem.php
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

/**
 * Menu item entity interface
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
interface IItem
{
	public const ROOT_ID = 'root';
	public const ROOT_NAME = 'root';

	/**
	 * @return string|int
	 */
	function getId();

	/**
	 * @return Entities\Menus\IMenu
	 */
	function getMenu() : Entities\Menus\IMenu;

	/**
	 * @param string|int $parentId
	 *
	 * @return void
	 */
	function setParentId($parentId) : void;

	/**
	 * @return string|int
	 */
	function getParentId();

	/**
	 * @return IItem|NULL
	 */
	function getParent() : ?IItem;

	/**
	 * @param string
	 *
	 * @return void
	 */
	function setName(string $name) : void;

	/**
	 * @return string
	 */
	function getName() : string;

	/**
	 * @param string $label
	 *
	 * @return void
	 */
	function setLabel(string $label) : void;

	/**
	 * @return string
	 */
	function getLabel() : string;

	/**
	 * @param string|array $target
	 *
	 * @return void
	 */
	function setTarget($target) : void;

	/**
	 * @return string|array|NULL
	 */
	function getTarget();

	/**
	 * @return bool
	 */
	function hasAbsoluteTarget() : bool;

	/**
	 * @param int $priority
	 *
	 * @return void
	 */
	function setPriority(int $priority) : void;

	/**
	 * @return int
	 */
	function getPriority() : int;

	/**
	 * @param array $attributes
	 *
	 * @return void
	 */
	function setAttributes(array $attributes) : void;

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return void
	 */
	function setAttribute(string $name, $value) : void;

	/**
	 * @return Utils\ArrayHash
	 */
	function getAttributes() : Utils\ArrayHash;

	/**
	 * @param string $name
	 * @param mixed|NULL $default
	 *
	 * @return mixed
	 */
	function getAttribute(string $name, $default = NULL);

	/**
	 * @return bool
	 */
	function isActive() : bool;

	/**
	 * @return bool
	 */
	function isVisible() : bool;

	/**
	 * @return string|NULL
	 */
	function getLink() : ?string;

	/**
	 * @param bool $default
	 *
	 * @return bool
	 */
	function hasAccess(bool $default = TRUE) : bool;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	function hasData(string $name) : bool;

	/**
	 * @param string|NULL $name
	 *
	 * @return mixed
	 */
	function getData(string $name = NULL);

	/**
	 * @param array $data
	 *
	 * @return void
	 */
	function setData(array $data) : void;

	/**
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return void
	 */
	function addData(string $name, $value) : void;

	/**
	 * @return bool
	 */
	function hasIcon() : bool;

	/**
	 * @return string|NULL
	 */
	function getIcon() : ?string;

	/**
	 * @param string $icon
	 *
	 * @return void
	 */
	function setIcon(string $icon) : void;

	/**
	 * @return bool
	 */
	function hasCounter() : bool;

	/**
	 * @return int|NULL
	 */
	function getCounter() : ?int;

	/**
	 * @param int $count
	 *
	 * @return void
	 */
	function setCounter(int $count) : void;
}
