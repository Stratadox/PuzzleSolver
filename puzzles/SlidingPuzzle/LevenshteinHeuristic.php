<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle;

use Stratadox\PuzzleSolver\Heuristic;
use Stratadox\PuzzleSolver\Puzzle;
use function assert;
use function levenshtein;

final class LevenshteinHeuristic implements Heuristic
{
    public function estimate(Puzzle $puzzle): float
    {
        assert($puzzle instanceof SlidingPuzzle);

        return levenshtein(
            $puzzle->goalState(),
            $puzzle->representation(),
            100,
            1,
            100
        );
    }
}
