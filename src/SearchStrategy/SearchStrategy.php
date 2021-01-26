<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

interface SearchStrategy
{
    public function isOngoing(): bool;
    /** @throws OutOfIterations */
    public function consider(Puzzle $puzzle): bool;
    public function nextCandidate(): Puzzle;
}
