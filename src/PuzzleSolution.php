<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use function assert;
use function implode;
use const PHP_EOL;

/**
 * Puzzle Solution
 *
 * The goal of this whole endeavour. Contains the solved state and all the
 * moves that led to that state, as well as a reference to the original state
 * of the puzzle that got solved.
 *
 * @author Stratadox
 */
final class PuzzleSolution implements Solution
{
    /** @var Moves */
    private $moves;
    /** @var Puzzle */
    private $state;
    /** @var Puzzle */
    private $original;

    public function __construct(Moves $moves, Puzzle $state, Puzzle $original)
    {
        $this->moves = $moves;
        $this->state = $state;
        $this->original = $original;
    }

    public static function fromSolved(Puzzle $puzzle, Puzzle $original): self
    {
        assert($puzzle->isSolved());
        return new self($puzzle->movesSoFar(), $puzzle, $original);
    }

    public function moves(): Moves
    {
        return $this->moves;
    }

    public function state(): Puzzle
    {
        return $this->state;
    }

    public function cost(): float
    {
        return $this->moves->cost();
    }

    public function original(): Puzzle
    {
        return $this->original;
    }

    public function __toString(): string
    {
        return implode(PHP_EOL, $this->moves->items());
    }
}
