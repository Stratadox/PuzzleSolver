<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see BreadthFirstStrategy
 *
 * @author Stratadox
 */
final class BreadthFirstStrategyFactory implements SearchStrategyFactory
{
    public static function make(): SearchStrategyFactory
    {
        return new self();
    }

    public function begin(Puzzle $puzzle): SearchStrategy
    {
        return BreadthFirstStrategy::forThe($puzzle);
    }
}
