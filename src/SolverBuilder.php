<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Solver Builder
 *
 * Utility to easily describe puzzles and produce a corresponding puzzle solver.
 *
 * @author Stratadox
 */
interface SolverBuilder
{
    public function withGoalTo(Find $goal): self;
    public function withWeightedMoves(): self;
    public function whereMovesEventuallyRunOut(): self;
    public function select(): PuzzleSolver;
}
