<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Puzzle
 *
 * The puzzle to solve, the solved puzzle or any of the states in between.
 * When you have a puzzle you wish to automatically solve, simply implement
 * this interface (potentially through an adapter) and give it to a solver.
 *
 * @author Stratadox
 */
interface Puzzle
{
    public function afterMaking(Move ...$moves): Puzzle;
    public function isSolved(): bool;
    public function movesSoFar(): Moves;
    public function representation(): string;
    public function possibleMoves(): Moves;
}
