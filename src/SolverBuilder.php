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
    public function withGoalTo(Find $goal): SolverBuilder;
    public function withWeightedMoves(): SolverBuilder;
    public function whereMovesEventuallyRunOut(): SolverBuilder;
    public function withHeuristic(Heuristic $heuristic): SolverBuilder;
    public function withLogging(string $file, string $separator, int $timeout = 0): SolverBuilder;
    public function select(): PuzzleSolver;
}
