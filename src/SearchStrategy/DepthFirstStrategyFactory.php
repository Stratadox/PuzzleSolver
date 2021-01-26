<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see DepthFirstStrategy
 *
 * @author Stratadox
 */
final class DepthFirstStrategyFactory implements SearchStrategyFactory
{
    public static function make(): SearchStrategyFactory
    {
        return new self();
    }

    public function begin(Puzzle $puzzle): SearchStrategy
    {
        return DepthFirstStrategy::forThe($puzzle);
    }
}
