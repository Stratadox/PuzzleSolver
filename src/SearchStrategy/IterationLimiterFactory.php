<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see IterationLimiter
 *
 * @author Stratadox
 */
final class IterationLimiterFactory implements SearchStrategyFactory
{
    /** @var SearchStrategyFactory */
    private $factory;
    /** @var int */
    private $limit;

    private function __construct(SearchStrategyFactory $factory, int $limit)
    {
        $this->factory = $factory;
        $this->limit = $limit;
    }

    public static function limitingTo(
        int $limit,
        SearchStrategyFactory $factory
    ): SearchStrategyFactory {
        return new self($factory, $limit);
    }

    public function begin(Puzzle $puzzle): SearchStrategy
    {
        return IterationLimiter::limitingTo($this->limit, $this->factory->begin($puzzle));
    }
}
