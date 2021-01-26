<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use function assert;
use const PHP_EOL;

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
    /** @var string|null */
    private $loggingFile;
    /** @var string|null */
    private $logSeparator;
    /** @var int */
    private $iterationInterval = 0;

    public function __construct(SolverFactory $solverFactory, ?Find $goal)
    {
        $this->create = $solverFactory;
        $this->goal = $goal;
    }

    public static function forAPuzzle(): SolverBuilder
    {
        return new self(new PuzzleSolverFactory(), null);
    }

    public static function aimingTo(Find $goal): SolverBuilder
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

    public function withLogging(string $file, string $separator, int $timeout = 0): SolverBuilder
    {
        $new = clone $this;
        $new->loggingFile = $file;
        $new->logSeparator = $separator;
        $new->iterationInterval = $timeout;
        return $new;
    }

    public function select(): PuzzleSolver
    {
        assert($this->goal);
        return $this->create->forAPuzzleWith(
            new PuzzleDescription(
                $this->goal,
                $this->weightedMoves,
                $this->exhausting,
                $this->heuristic
            ),
            new SearchSettings(
                $this->loggingFile,
                $this->logSeparator ?: PHP_EOL,
                $this->iterationInterval
            )
        );
    }
}
