<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use Stratadox\PuzzleSolver\Heuristic;
use Stratadox\PuzzleSolver\Puzzle;
use function assert;

final class CrateHeuristic implements Heuristic
{
    public function estimate(Puzzle $puzzle): float
    {
        assert($puzzle instanceof SlidingCratesPuzzle);
        return $puzzle->minimumDistanceToGoal();
    }
}
