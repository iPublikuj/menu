<?php
/**
 * FilterIterator.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec https://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Menu\Filters;

use IPub\Menu\Entities;

/**
 * Menu filter iterator
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Filters
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
abstract class FilterIterator extends \FilterIterator
{
	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @param Entities\Items\IItem[]|\Iterator $iterator
	 * @param array $options
	 */
	public function __construct(\Iterator $iterator, array $options = [])
	{
		parent::__construct($iterator);

		$this->options = $options;
	}
}
