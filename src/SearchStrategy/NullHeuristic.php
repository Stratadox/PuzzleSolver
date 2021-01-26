<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Heuristic;
use Stratadox\PuzzleSolver\Puzzle;

final class NullHeuristic implements Heuristic
{
    public function estimate(Puzzle $puzzle): float
    {
        return 0;
    }
}
