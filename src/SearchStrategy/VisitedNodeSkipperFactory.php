<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see VisitedNodeSkipper
 *
 * @author Stratadox
 */
final class VisitedNodeSkipperFactory implements SearchStrategyFactory
{
    /** @var SearchStrategyFactory */
    private $factory;

    private function __construct(SearchStrategyFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function using(SearchStrategyFactory $factory): SearchStrategyFactory
    {
        return new self($factory);
    }

    public function begin(Puzzle $puzzle): SearchStrategy
    {
        return VisitedNodeSkipper::forThe($this->factory->begin($puzzle));
    }
}
