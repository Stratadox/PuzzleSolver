<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see WorseThanBestSolutionSkipper
 *
 * @author Stratadox
 */
final class WorseThanBestSolutionSkipperFactory implements SearchStrategyFactory
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
        return WorseThanBestSolutionSkipper::forThe($this->factory->begin($puzzle));
    }
}
