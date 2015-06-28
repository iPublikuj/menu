<?php
/**
 * Node.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:Menu!
 * @subpackage	common
 * @since		5.0
 *
 * @date		26.08.14
 */

namespace IPub\Menu;

use Nette;

class Node extends Nette\Object implements \IteratorAggregate, \Countable
{
	/**
	 * @var Node|NULL
	 */
	protected $parent;

	/**
	 * @var Node[]
	 */
	protected $children = [];

	/**
	 * @var Entities\IItem
	 */
	protected $item;

	/**
	 * @param Entities\IItem $item
	 */
	public function setItem(Entities\IItem $item)
	{
		$this->item = $item;
	}

	/**
	 * @return Entities\IItem
	 */
	public function getItem()
	{
		return $this->item;
	}

	/**
	 * @param  string $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function getAttribute($key, $default = NULL)
	{
		return $this->item->getAttribute($key, $default);
	}

	/**
	 * @return string|int
	 */
	public function getId()
	{
		return $this->item->getId();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->item->getName();
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->item->getUrl();
	}

	/**
	 * @return Node|NULL
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Sets the parent node.
	 *
	 * @param  Node|NULL $parent
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function setParent(Node $parent = NULL)
	{
		if ($parent === $this) {
			throw new \InvalidArgumentException('A node cannot have itself as a parent');
		}

		if ($parent === $this->parent) {
			return $this;
		}

		if ($this->parent !== NULL) {
			$this->parent->remove($this);
		}

		$this->parent = $parent;

		if ($this->parent !== NULL && !$this->parent->contains($this, FALSE)) {
			$this->parent->add($this);
		}

		return $this;
	}

	/**
	 * Checks for child nodes.
	 *
	 * @return bool
	 */
	public function hasChildren()
	{
		return !empty($this->children);
	}

	/**
	 * Gets all child nodes.
	 *
	 * @return Node[]
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Adds a node.
	 *
	 * @param  Node $node
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function add(Node $node)
	{
		//$this->children[$node->getItem()->getId()] = $node->setParent($this);
		$this->children[$node->hashCode()] = $node->setParent($this);

		return $this;
	}

	/**
	 * Add an array of nodes.
	 *
	 * @param  Node[]  $nodes
	 * @return self
	 */
	public function addAll(array $nodes)
	{
		foreach ($nodes as $node) {
			$this->add($node);
		}

		return $this;
	}

	/**
	 * Removes a node.
	 *
	 * @param  Node|string $node
	 * @return bool
	 */
	public function remove($node)
	{
		$hash = $node instanceof Node ? $node->hashCode() : (string) $node;

		if ($node = $this->find($hash)) {

			unset($this->children[$hash]);
			$node->setParent(NULL);

			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Removes all nodes or an given array of nodes.
	 *
	 * @param  (Node|string)[] $nodes
	 * @return bool
	 */
	public function removeAll(array $nodes = [])
	{
		if (empty($nodes)) {

			foreach ($this->children as $child) {
				$child->setParent(NULL);
			}

			$this->children = [];

			return TRUE;
		}

		$bool = FALSE;

		foreach ($nodes as $node) {
			if ($this->remove($node)) {
				$bool = TRUE;
			}
		}

		return $bool;
	}

	/**
	 * Find a node by its hashcode.
	 *
	 * @param  string $hash
	 * @param  bool   $recursive
	 * @return Node|NULL
	 */
	public function find($hash, $recursive = TRUE)
	{
		$node = isset($this->children[$hash]) ? $this->children[$hash] : NULL;

		if (!$node && $recursive) {
			foreach(new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST) as $n) {
				if ($n->hashCode() === $hash) {
					return $n;
				}
			}
		}

		return $node;
	}

	/**
	 * Checks if the tree contains the given node.
	 *
	 * @param  Node|string $node
	 * @param  bool        $recursive
	 * @return bool
	 */
	public function contains($node, $recursive = TRUE)
	{
		return $this->find(($node instanceof Node ? $node->hashCode() : (string) $node), $recursive) !== NULL;
	}

	/**
	 * Gets the nodes depth.
	 *
	 * @return int
	 */
	public function getDepth()
	{
		if ($this->parent === NULL) {
			return 0;
		}

		return $this->parent->getDepth() + 1;
	}

	/**
	 * Returns a hashcode as unique identifier for a node.
	 *
	 * @return string
	 */
	public function hashCode()
	{
		return spl_object_hash($this);
	}

	/**
	 * Gets an iterator for iterating over the tree nodes.
	 *
	 * @return Iterator\RecursiveIterator
	 */
	public function getIterator()
	{
		return new Iterator\RecursiveIterator($this);
	}

	/**
	 * Returns the number of children.
	 *
	 * @return int
	 */
	public function count()
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
	 * @param  string $method
	 * @param  array  $args
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public function __call($method, $args)
	{
		if (!$this->item) {
			return;
		}

		if (!is_callable($callable = [$this->item, $method])) {
			throw new \InvalidArgumentException(sprintf('Undefined method call "%s::%s"', get_class($this->item), $method));
		}

		return call_user_func_array($callable, $args);
	}
}