<?php
/**
 * TMenu.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     common
 * @since          1.0.0
 *
 * @date           05.04.14
 */

declare(strict_types = 1);

namespace IPub\Menu;

use IPub;
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
	public function injectMenu(Managers\MenuManager $menuManager)
	{
		$this->menuManager = $menuManager;
	}
}
