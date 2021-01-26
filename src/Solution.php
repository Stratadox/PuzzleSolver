<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Solution
 *
 * The goal of this whole endeavour. Contains the solved state and all the
 * moves that led to that state, as well as a reference to the original state
 * of the puzzle that got solved.
 *
 * @author Stratadox
 */
interface Solution
{
    public function moves(): Moves;
    public function state(): Puzzle;
    public function original(): Puzzle;
    public function cost(): float;
    public function __toString(): string;
}
