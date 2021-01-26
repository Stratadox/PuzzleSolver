<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Solver;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\PuzzleSolution;
use Stratadox\PuzzleSolver\SearchStrategy\SearchStrategyFactory;
use Stratadox\PuzzleSolver\Solutions;

/**
 * Lazy puzzle solvers will continue to look for solutions until they've found
 * *all* valid solutions. Lazy solvers can be used for puzzles that require more
 * than one solution.
 *
 * @author Stratadox
 */
final class LazySolver implements PuzzleSolver
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
        $solutions = [];

        while ($search->isOngoing()) {
            $puzzleState = $search->nextCandidate();

            if ($puzzleState->isSolved()) {
                $solutions[] = PuzzleSolution::fromSolved($puzzleState, $puzzle);
            }

            foreach ($puzzleState->possibleMoves() as $move) {
                $search->consider($puzzleState->afterMaking($move));
            }
        }

        return new Solutions(...$solutions);
    }
}
