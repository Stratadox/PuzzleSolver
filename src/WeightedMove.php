<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Weighted Move
 *
 * By default, all moves have a cost of 1. The "best" or "cheapest" solution is
 * the solution that requires the fewest steps to resolve. If your puzzle has
 * a different way of measuring the cost of a move, the moves should implement
 * this interface instead.
 *
 * @author Stratadox
 */
interface WeightedMove extends Move
{
    public function cost(): float;
}
