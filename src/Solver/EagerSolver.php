<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Solver;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\NoPossibleSolution;
use Stratadox\PuzzleSolver\PuzzleSolution;
use Stratadox\PuzzleSolver\SearchStrategy\SearchStrategyFactory;
use Stratadox\PuzzleSolver\Solutions;

/**
 * Eager puzzle solvers will search for the first valid solution. Eager solvers
 * will stop after finding a single solution, making them neat for puzzles that
 * can have only one possible solution, or require only a single solution.
 *
 * @author Stratadox
 */
final class EagerSolver implements PuzzleSolver
{
    /** @var SearchStrategyFactory */
    private $strategy;

    private function __construct(SearchStrategyFactory $strategy)
    {
        $this->strategy = $strategy;
    }

    public static function using(SearchStrategyFactory $strategy): PuzzleSolver
    {
        return new self($strategy);
    }

    public function solve(Puzzle $puzzle): Solutions
    {
        $search = $this->strategy->begin($puzzle);

        while ($search->isOngoing()) {
            $puzzleState = $search->nextCandidate();

            if ($puzzleState->isSolved()) {
                return new Solutions(PuzzleSolution::fromSolved($puzzleState, $puzzle));
            }

            foreach ($puzzleState->possibleMoves() as $move) {
                $search->consider($puzzleState->afterMaking($move));
            }
        }

        throw NoPossibleSolution::to($puzzle);
    }
}
