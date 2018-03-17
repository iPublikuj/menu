<?php
/**
 * TMenu.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           05.04.14
 */

declare(strict_types = 1);

namespace IPub\Menu;

use IPub\Menu\Managers;

/**
 * Menu trait for presenters & components
 *
 * @package        iPublikuj:Menu!
 * @subpackage     common
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
trait TMenu
{
	/**
	 * @var Managers\MenuManager
	 */
	protected $menuManager;

	/**
	 * @param Managers\MenuManager $menuManager
	 *
	 * @return void
	 */
	public function injectMenu(Managers\MenuManager $menuManager) : void
	{
		$this->menuManager = $menuManager;
	}

	/**
	 * @return Managers\MenuManager
	 */
	public function getMenuManager() : Managers\MenuManager
	{
		return $this->menuManager;
	}
}
