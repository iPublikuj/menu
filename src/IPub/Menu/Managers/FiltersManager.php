<?php
/**
 * FilterManager.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 * @since          1.0.0
 *
 * @date           26.06.15
 */

declare(strict_types = 1);

namespace IPub\Menu\Managers;

use Nette;

use IPub;
use IPub\Menu\Exceptions;
use IPub\Menu\Filters;

/**
 * Menu filters manager
 *
 * @package        iPublikuj:Menu!
 * @subpackage     Managers
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class FiltersManager extends Nette\Object implements IFiltersManager
{
	/**
	 * @var \SplPriorityQueue
	 */
	private $filters = [];

	/**
	 * @var Filters\IFactory[]
	 */
	private $factories = [];

	public function __construct()
	{
		$this->filters = new \SplPriorityQueue();
	}

	/**
	 * {@inheritdoc}
	 */
	public function has(string $name) : bool
	{
		return isset($this->factories[$name]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get(string $name)
	{
		return $this->has($name) ? $this->factories[$name] : NULL;
	}

	/**
	 * {@inheritdoc}
	 */
	public function register(Filters\IFactory $filter, string $name, int $priority = 0)
	{
		$this->unregister($name);

		$this->filters->insert($name, $priority);
		$this->factories[$name] = $filter;
	}

	/**
	 * {@inheritdoc}
	 */
	public function unregister(string $name)
	{
		if ($this->has($name)) {
			unset($this->factories[$name]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getIterator() : \ArrayIterator
	{
		$filters = [];

		$this->filters->rewind();

		$restore = new \SplPriorityQueue;
		$priority = 999;

		while ($this->filters->valid())
		{
			$filter = $this->filters->current();

			if ($this->has($filter)) {
				$filters[$filter] = $this->get($filter);
			}

			$restore->insert($filter, $priority);

			$this->filters->next();
		}

		$this->filters = $restore;

		return new \ArrayIterator($filters);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetExists($offset)
	{
		return isset($this->factories[$offset]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetGet($offset)
	{
		return $this->factories[$offset];
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetSet($offset, $value)
	{
		throw new Exceptions\InvalidStateException('Use "register" method for adding item.');
	}

	/**
	 * {@inheritdoc}
	 */
	public function offsetUnset($offset)
	{
		throw new Exceptions\InvalidStateException('Use "unregister" method for removing item.');
	}
}
