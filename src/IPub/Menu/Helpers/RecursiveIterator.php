<?php
/**
 * RecursiveIterator.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Helpers
 * @since          1.0.0
 *
 * @date           26.08.14
 */

namespace IPub\Menu\Helpers;

use IPub\Menu\Entities;

final class RecursiveIterator extends \ArrayIterator implements \RecursiveIterator
{
	/**
	 * @param Entities\Nodes\Node $node
	 */
	public function __construct(Entities\Nodes\Node $node)
	{
		parent::__construct($node->getChildren());
	}

	/**
	 * Returns if an iterator can be created for the current element
	 *
	 * @return bool
	 */
	public function hasChildren() : bool
	{
		return $this->current()->hasChildren();
	}

	/**
	 * Returns an iterator for the current element.
	 *
	 * @return mixed
	 */
	public function getChildren()
	{
		return new self($this->current());
	}
}
