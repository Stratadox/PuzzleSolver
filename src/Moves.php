<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use Stratadox\ImmutableCollection\Appending;
use Stratadox\ImmutableCollection\Filtering;
use Stratadox\ImmutableCollection\ImmutableCollection;

/**
 * Collection of @see Move objects
 *
 * @author Stratadox
 */
final class Moves extends ImmutableCollection
{
    use Appending, Filtering;

    public function __construct(Move ...$moves)
    {
        parent::__construct(...$moves);
    }

    public function current(): Move
    {
        return parent::current();
    }

    public static function none(): self
    {
        return new self();
    }

    public function cost(): float
    {
        $cost = 0;
        foreach ($this as $move) {
            if ($move instanceof WeightedMove) {
                $cost += $move->cost();
            } else {
                ++$cost;
            }
        }
        return $cost;
    }
}
