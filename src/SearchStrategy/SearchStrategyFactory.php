<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

interface SearchStrategyFactory
{
    public function begin(Puzzle $puzzle): SearchStrategy;
}
