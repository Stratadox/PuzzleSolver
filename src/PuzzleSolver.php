<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Puzzle Solver
 *
 * Unified interface for puzzle solvers. Classes that implement this interface
 * are able to (at least attempt to) solve a puzzle. Depending on the
 * configuration of the solver in question, it will find one or more solutions,
 * throw a no-solution exception or (worst case) get stuck in an eternal loop.
 *
 * @author Stratadox
 */
interface PuzzleSolver
{
    /** @throws NoSolution */
    public function solve(Puzzle $puzzle): Solutions;
}
