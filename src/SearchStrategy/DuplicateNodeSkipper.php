<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;

/**
 * Duplicate Node Skipper
 *
 * Duplicate Node Skipper prevents paths from getting into loops.
 * Before considering a new candidate, all previous puzzle states are compared
 * with the new state. If the new puzzle state was already reached on this very
 * path, the candidate is considered a loop and gets rejected.
 *
 * Decorator for other search strategies.
 *
 * @author Stratadox
 */
final class DuplicateNodeSkipper implements SearchStrategy
{
    /** @var SearchStrategy */
    private $search;
    /** @var Puzzle */
    private $puzzle;

    public function __construct(SearchStrategy $search, Puzzle $puzzle)
    {
        $this->search = $search;
        $this->puzzle = $puzzle;
    }

    public static function forThe(Puzzle $puzzle, SearchStrategy $search): SearchStrategy
    {
        return new self($search, $puzzle);
    }

    public function isOngoing(): bool
    {
        return $this->search->isOngoing();
    }

    public function consider(Puzzle $puzzle): bool
    {
        if ($this->shouldSkip($this->puzzle, $puzzle->representation(), ...$puzzle->movesSoFar())) {
            return false;
        }
        return $this->search->consider($puzzle);
    }

    public function nextCandidate(): Puzzle
    {
        return $this->search->nextCandidate();
    }

    private function shouldSkip(
        Puzzle $visited,
        string $current,
        Move ...$moves
    ): bool {
        foreach ($moves as $move) {
            if ($visited->representation() === $current) {
                return true;
            }
            $visited = $visited->afterMaking($move);
        }
        return false;
    }
}
