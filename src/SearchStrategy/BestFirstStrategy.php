<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use SplPriorityQueue;
use Stratadox\PuzzleSolver\Heuristic;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;

/**
 * Best-first search strategy
 *
 * Best-first searches enqueue newly encountered nodes by order of quality. The
 * move that is most likely to lead to the desired outcome is considered first.
 *
 * @author Stratadox
 */
final class BestFirstStrategy implements SearchStrategy
{
    /** @var SplPriorityQueue */
    private $queue;
    /** @var Puzzle */
    private $originalPuzzle;
    /** @var Heuristic */
    private $heuristic;

    private function __construct(
        SplPriorityQueue $queue,
        Puzzle $originalPuzzle,
        Heuristic $heuristic
    ) {
        $this->queue = $queue;
        $this->originalPuzzle = $originalPuzzle;
        $this->heuristic = $heuristic;
    }

    public static function withHeuristic(
        Heuristic $heuristic,
        Puzzle $puzzle
    ): SearchStrategy {
        $queue = new SplPriorityQueue();
        $queue->insert(Moves::none(), 0); // @todo heuristic
        return new self($queue, $puzzle, $heuristic);
    }

    public function isOngoing(): bool
    {
        return !$this->queue->isEmpty();
    }

    public function consider(Puzzle $puzzle): bool
    {
        $this->insert($puzzle->movesSoFar(), $this->heuristic->estimate($puzzle));
        return true;
    }

    public function nextCandidate(): Puzzle
    {
        return $this->originalPuzzle->afterMaking(...$this->queue->extract());
    }

    private function insert(Moves $moves, float $heuristic): void
    {
        $this->queue->insert($moves, -($moves->cost() + $heuristic));
    }
}
