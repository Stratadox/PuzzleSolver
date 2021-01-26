<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Heuristic;
use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see BestFirstStrategy
 *
 * @author Stratadox
 */
final class BestFirstStrategyFactory implements SearchStrategyFactory
{
    /** @var Heuristic */
    private $heuristic;

    private function __construct(Heuristic $heuristic)
    {
        $this->heuristic = $heuristic;
    }

    public static function noHeuristic(): SearchStrategyFactory
    {
        return new self(new NullHeuristic());
    }

    public static function withHeuristic(Heuristic $heuristic): SearchStrategyFactory
    {
        return new self($heuristic);
    }

    public function begin(Puzzle $puzzle): SearchStrategy
    {
        return BestFirstStrategy::withHeuristic($this->heuristic, $puzzle);
    }
}
