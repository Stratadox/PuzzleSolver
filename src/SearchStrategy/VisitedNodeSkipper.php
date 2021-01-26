<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Visited Node Skipper
 *
 * The Visited Node Skipper keeps a list of puzzle states that have already been
 * encountered during the search to skip candidates when they are about to be
 * considered for a second time.
 *
 * Decorator for other search strategies.
 *
 * @author Stratadox
 */
final class VisitedNodeSkipper implements SearchStrategy
{
    /** @var SearchStrategy */
    private $search;
    /** @var bool[] */
    private $visited = [];

    public function __construct(SearchStrategy $search)
    {
        $this->search = $search;
    }

    public static function forThe(SearchStrategy $search): SearchStrategy
    {
        return new self($search);
    }

    public function isOngoing(): bool
    {
        return $this->search->isOngoing();
    }

    public function consider(Puzzle $puzzle): bool
    {
        if (isset($this->visited[$puzzle->representation()])) {
            return false;
        }
        return $this->search->consider($puzzle);
    }

    public function nextCandidate(): Puzzle
    {
        $state = $this->search->nextCandidate();
        if (!$state->isSolved()) {
            $this->visited[$state->representation()] = true;
        }
        return $state;
    }
}
