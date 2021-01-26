<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use SplStack;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;

/**
 * Depth-first search strategy
 *
 * Depth-first searches continuously follow the first option, until it turns out
 * the branch does not lead to a solution, at which point it tracks back to the
 * first next available option.
 *
 * @author Stratadox
 */
final class DepthFirstStrategy implements SearchStrategy
{
    /** @var SplStack */
    private $stack;
    /** @var Puzzle */
    private $originalPuzzle;

    private function __construct(
        SplStack $stack,
        Puzzle $originalPuzzle
    ) {
        $this->stack = $stack;
        $this->originalPuzzle = $originalPuzzle;
    }

    public static function forThe(Puzzle $puzzle): SearchStrategy
    {
        $stack = new SplStack();
        $stack->push(Moves::none());
        return new self($stack, $puzzle);
    }

    public function isOngoing(): bool
    {
        return !$this->stack->isEmpty();
    }

    public function consider(Puzzle $puzzle): bool
    {
        $this->stack->push($puzzle->movesSoFar());
        return true;
    }

    public function nextCandidate(): Puzzle
    {
        return $this->originalPuzzle->afterMaking(...$this->stack->pop());
    }
}
