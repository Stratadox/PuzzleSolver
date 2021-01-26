<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use SplQueue;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;

/**
 * Breadth-first search strategy
 *
 * Breadth-first searches continuously explore all available options, before
 * passing into the options available after those.
 *
 * @author Stratadox
 */
final class BreadthFirstStrategy implements SearchStrategy
{
    /** @var SplQueue */
    private $queue;
    /** @var Puzzle */
    private $originalPuzzle;

    private function __construct(
        SplQueue $queue,
        Puzzle $originalPuzzle
    ) {
        $this->queue = $queue;
        $this->originalPuzzle = $originalPuzzle;
    }

    public static function forThe(Puzzle $puzzle): SearchStrategy
    {
        $queue = new SplQueue();
        $queue->enqueue(Moves::none());
        return new self($queue, $puzzle);
    }

    public function isOngoing(): bool
    {
        return !$this->queue->isEmpty();
    }

    public function consider(Puzzle $puzzle): bool
    {
        $this->queue->enqueue($puzzle->movesSoFar());
        return true;
    }

    public function nextCandidate(): Puzzle
    {
        return $this->originalPuzzle->afterMaking(...$this->queue->dequeue());
    }
}
