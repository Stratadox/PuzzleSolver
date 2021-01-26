<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use Stratadox\PuzzleSolver\SearchStrategy\BestFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\BreadthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DuplicateNodeSkipperFactory;
use Stratadox\PuzzleSolver\SearchStrategy\SearchStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeCostCheckerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeSkipperFactory;
use Stratadox\PuzzleSolver\SearchStrategy\WorseThanBestSolutionSkipperFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;
use Stratadox\PuzzleSolver\Solver\LazySolver;

/**
 * Factory for @see PuzzleSolver
 *
 * @author Stratadox
 */
final class PuzzleSolverFactory implements SolverFactory
{
    public function forAPuzzleWith(PuzzleDescription $puzzle): PuzzleSolver
    {
        return $puzzle->singleSolution() ?
            EagerSolver::using($this->eagerStrategy($puzzle)) :
            LazySolver::using($this->lazyStrategy($puzzle));
    }

    /**
     * Retrieves the eagerStrategy, based on the current description.
     *
     * @return SearchStrategyFactory
     *
     * @todo Add more test puzzles that require a single solution, to challenge
     *       the assumptions currently made.
     */
    private function eagerStrategy(PuzzleDescription $puzzle): SearchStrategyFactory
    {
        if (null !== $puzzle->heuristic()) {
            return VisitedNodeCostCheckerFactory::using(
                BestFirstStrategyFactory::withHeuristic($puzzle->heuristic())
            );
        }
        if ($puzzle->isWeightedMoves()) {
            return VisitedNodeCostCheckerFactory::using(
                BestFirstStrategyFactory::noHeuristic()
            );
        }
        return VisitedNodeSkipperFactory::using(
            $puzzle->isExhausting() ?
                DepthFirstStrategyFactory::make() :
                BreadthFirstStrategyFactory::make()
        );
    }

    /**
     * Retrieves the lazyStrategy, based on the current description.
     *
     * @return SearchStrategyFactory
     *
     * @todo Add more test puzzles that require multiple solutions, to challenge
     *       the assumptions currently made.
     */
    private function lazyStrategy(PuzzleDescription $puzzle): SearchStrategyFactory
    {
        if ($puzzle->isExhausting()) {
            return VisitedNodeSkipperFactory::using(
                DepthFirstStrategyFactory::make()
            );
        }
        if ($puzzle->onlyBest()) {
            return WorseThanBestSolutionSkipperFactory::using(
                BreadthFirstStrategyFactory::make()
            );
        }
        return DuplicateNodeSkipperFactory::using(
            DepthFirstStrategyFactory::make()
        );
    }
}
