<?php
/**
 * Node.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	Iterator
 * @since		5.0
 *
 * @date		26.08.14
 */

namespace IPub\Menu\Iterator;

use IPub\Menu\Node;

class RecursiveIterator extends \ArrayIterator implements \RecursiveIterator
{
	/**
	 * Constructor.
	 */
	public function __construct(Node $node)
	{
		parent::__construct($node->getChildren());
	}

	/**
	 * Returns if an iterator can be created for the current element.
	 *
	 * @return bool
	 */
	public function hasChildren()
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