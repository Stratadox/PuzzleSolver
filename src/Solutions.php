<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use Stratadox\ImmutableCollection\ImmutableCollection;
use function implode;
use const PHP_EOL;

/**
 * Collection of @see Solution objects
 *
 * @author Stratadox
 */
final class Solutions extends ImmutableCollection
{
    public function __construct(Solution ...$solutions)
    {
        parent::__construct(...$solutions);
    }

    public function current(): Solution
    {
        return parent::current();
    }

    public function __toString(): string
    {
        return implode(
            PHP_EOL . PHP_EOL . "---" . PHP_EOL . PHP_EOL,
            $this->items()
        );
    }
}
