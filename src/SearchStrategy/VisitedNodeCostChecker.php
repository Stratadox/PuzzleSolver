<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;
use const INF;

/**
 * Visited Node Cost Checker
 *
 * The Visited Node Cost Checker keeps a record of the cost of reaching each
 * considered candidate. When a candidate is encountered that already has a
 * recorded cost, the cost of the newly discovered path to reach the same state
 * as before is compared with the previously recorded cost. If the cost is lower
 * than before, the record gets updated and the new candidate is considered. In
 * case the cost is equal or higher than previous paths to the same goal, the
 * candidate is discarded.
 *
 * Decorator for other search strategies.
 *
 * @author Stratadox
 */
final class VisitedNodeCostChecker implements SearchStrategy
{
    /** @var float[] */
    private $costTowards = [];
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
        $cost = $puzzle->movesSoFar()->cost();
        if ($cost < ($this->costTowards[$puzzle->representation()] ?? INF)) {
            $this->costTowards[$puzzle->representation()] = $cost;
            return $this->search->consider($puzzle);
        }
        return false;
    }

    public function nextCandidate(): Puzzle
    {
        return $this->search->nextCandidate();
    }
}
