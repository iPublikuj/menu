<?php
/**
 * Node.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Entities
 * @since          5.0
 *
 * @date           26.08.14
 */

namespace IPub\Menu\Entities\Nodes;

use Nette;

use IPub;
use IPub\Menu\Entities;
use IPub\Menu\Exceptions;
use IPub\Menu\Helpers;

final class Node extends Nette\Object implements \IteratorAggregate, \Countable
{
	/**
	 * @var Entities\Items\IItem
	 */
	private $item;

	/**
	 * @var Node|NULL
	 */
	private $parent;

	/**
	 * @var Node[]
	 */
	private $children = [];

	/**
	 * @param Entities\Items\IItem $item
	 */
	public function __construct(Entities\Items\IItem $item)
	{
		$this->item = $item;
	}

	/**
	 * @return string|int
	 */
	public function getId()
	{
		return $this->item->getId();
	}

	/**
	 * @return Entities\Items\IItem
	 */
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * @param  Node|NULL $parent
	 *
	 * @return void
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function setParent(Node $parent = NULL)
	{
		if ($parent === $this) {
			throw new Exceptions\InvalidArgumentException('A node cannot have itself as a parent');
		}

		if ($parent === $this->parent) {
			return;
		}

		if ($this->parent !== NULL) {
			$this->parent->removeChild($this);
		}

		$this->parent = $parent;

		if ($this->parent !== NULL && !$this->parent->contains($this, FALSE)) {
			$this->parent->addChild($this);
		}
	}

	/**
	 * @return Node|NULL
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param Node[] $nodes
	 *
	 * @return void
	 */
	public function setChildren(array $nodes)
	{
		foreach ($nodes as $node) {
			$this->addChild($node);
		}
	}

	/**
	 * @param Node $node
	 *
	 * @return void
	 */
	public function addChild(Node $node)
	{
		$node->setParent($this);

		$this->children[$node->hashCode()] = $node;
	}

	/**
	 * @return bool
	 */
	public function hasChildren()
	{
		return !empty($this->children);
	}

	/**
	 * @return Node[]
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * @param Node|string $node
	 *
	 * @return bool
	 */
	public function removeChild($node) : bool
	{
		$hash = $node instanceof Node ? $node->hashCode() : (string) $node;

		if ($node = $this->findChild($hash)) {
			unset($this->children[$hash]);

			$node->setParent(NULL);

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * @param Node[]|string[] $nodes
	 *
	 * @return bool
	 */
	public function removeAll(array $nodes = []) : bool
	{
		if ($nodes !== []) {
			foreach ($this->children as $child) {
				$child->setParent(NULL);
			}

			$this->children = [];

			return TRUE;
		}

		$bool = FALSE;

		foreach ($nodes as $node) {
			if ($this->removeChild($node)) {
				$bool = TRUE;
			}
		}

		return $bool;
	}

	/**
	 * @param string $hash
	 * @param bool $recursive
	 *
	 * @return Node|NULL
	 */
	public function findChild($hash, $recursive = TRUE)
	{
		$node = isset($this->children[$hash]) ? $this->children[$hash] : NULL;

		if (!$node && $recursive) {
			foreach (new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST) as $n) {
				if ($n->hashCode() === $hash) {
					return $n;
				}
			}
		}

		return $node;
	}

	/**
	 * @param  Node|string $node
	 * @param  bool $recursive
	 *
	 * @return bool
	 */
	public function contains($node, $recursive = TRUE)
	{
		return $this->findChild(($node instanceof Node ? $node->hashCode() : (string) $node), $recursive) !== NULL;
	}

	/**
	 * @return int
	 */
	public function getDepth() : int
	{
		if ($this->parent === NULL) {
			return 0;
		}

		return $this->parent->getDepth() + 1;
	}

	/**
	 * @return string
	 */
	public function hashCode() : string
	{
		return spl_object_hash($this);
	}

	/**
	 * @return Helpers\RecursiveIterator
	 */
	public function getIterator() : Helpers\RecursiveIterator
	{
		return new Helpers\RecursiveIterator($this);
	}

	/**
	 * @return int
	 */
	public function count() : int
	{
		return count($this->children);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->item;
	}

	/**
	 * @param string $method
	 * @param array $args
	 *
	 * @return mixed
	 *
	 * @throws Exceptions\InvalidArgumentException
	 */
	public function __call($method, $args)
	{
		if (!is_callable($callable = [$this->item, $method])) {
			throw new Exceptions\InvalidArgumentException(sprintf('Undefined method call "%s::%s"', get_class($this->item), $method));
		}

		return call_user_func_array($callable, $args);
	}
}
