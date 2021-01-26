<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;
use const INF;

/**
 * Worse-Than-Best Solution Skipper
 *
 * The Worse-Than-Best Solution Skipper keeps track of the lowest cost of the
 * solutions that have been found during the search. Each time a node is
 * encountered of which the path cost is more than the cost of the cheapest
 * solution that has already been found, the node is skipped.
 *
 * @author Stratadox
 */
final class WorseThanBestSolutionSkipper implements SearchStrategy
{
    /** @var float */
    private $lowestSolutionCost = INF;
    /** @var SearchStrategy */
    private $search;

    private function __construct(SearchStrategy $search)
    {
        $this->search = $search;
    }

    public static function forThe(SearchStrategy $search): self
    {
        return new self($search);
    }

    public function isOngoing(): bool
    {
        return $this->search->isOngoing();
    }

    public function consider(Puzzle $puzzle): bool
    {
        if ($puzzle->movesSoFar()->cost() > $this->lowestSolutionCost) {
            return false;
        }
        return $this->search->consider($puzzle);
    }

    public function nextCandidate(): Puzzle
    {
        $puzzle = $this->search->nextCandidate();
        if (
            $puzzle->isSolved() &&
            ($cost = $puzzle->movesSoFar()->cost()) < $this->lowestSolutionCost
        ) {
            $this->lowestSolutionCost = $cost;
        }
        return $puzzle;
    }
}
