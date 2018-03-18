<?php
/**
 * InvalidStateException.php
 *
 * @copyright      More in license.md
 * @license        https://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:Menu!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           28.06.15
 */

declare(strict_types = 1);

namespace IPub\Menu\Exceptions;

use Nette;

class InvalidStateException extends Nette\InvalidStateException implements IException
{
}
