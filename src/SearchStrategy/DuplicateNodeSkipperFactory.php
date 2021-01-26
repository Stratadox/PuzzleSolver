<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Factory for @see DuplicateNodeSkipper
 *
 * @author Stratadox
 */
final class DuplicateNodeSkipperFactory implements SearchStrategyFactory
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
        return DuplicateNodeSkipper::forThe($puzzle, $this->factory->begin($puzzle));
    }
}
