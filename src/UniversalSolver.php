<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use function assert;

/**
 * Universal Solver
 *
 * Utility to easily describe puzzles and produce a corresponding puzzle solver.
 *
 * @author Stratadox
 */
final class UniversalSolver implements SolverBuilder
{
    /** @var SolverFactory */
    private $create;
    /** @var Find|null */
    private $goal;
    /** @var bool */
    private $weightedMoves = false;
    /** @var bool */
    private $exhausting = false;
    /** @var Heuristic|null */
    private $heuristic;

    public function __construct(SolverFactory $solverFactory, ?Find $goal)
    {
        $this->create = $solverFactory;
        $this->goal = $goal;
    }

    public static function forAPuzzle(): self
    {
        return new self(new PuzzleSolverFactory(), null);
    }

    public static function aimingTo(Find $goal): self
    {
        return new self(new PuzzleSolverFactory(), $goal);
    }

    public function withGoalTo(Find $goal): SolverBuilder
    {
        $new = clone $this;
        $new->goal = $goal;
        return $new;
    }

    public function withWeightedMoves(): SolverBuilder
    {
        $new = clone $this;
        $new->weightedMoves = true;
        return $new;
    }

    public function whereMovesEventuallyRunOut(): SolverBuilder
    {
        $new = clone $this;
        $new->exhausting = true;
        return $new;
    }

    public function withHeuristic(Heuristic $heuristic): SolverBuilder
    {
        $new = clone $this;
        $new->heuristic = $heuristic;
        return $new;
    }

    public function select(): PuzzleSolver
    {
        assert($this->goal);
        return $this->create->forAPuzzleWith(new PuzzleDescription(
            $this->goal,
            $this->weightedMoves,
            $this->exhausting,
            $this->heuristic
        ));
    }
}
