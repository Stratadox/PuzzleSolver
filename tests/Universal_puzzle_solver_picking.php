<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\CrateHeuristic;
use Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle\LevenshteinHeuristic;
use Stratadox\PuzzleSolver\SearchStrategy\BestFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\BreadthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DuplicateNodeSkipperFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeCostCheckerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeSkipperFactory;
use Stratadox\PuzzleSolver\SearchStrategy\WorseThanBestSolutionSkipperFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;
use Stratadox\PuzzleSolver\Solver\LazySolver;
use Stratadox\PuzzleSolver\UniversalSolver;

/**
 * @testdox Universal puzzle solver picking
 */
class Universal_puzzle_solver_picking extends TestCase
{
    /** @test */
    function picking_the_eager_maze_puzzle_solver()
    {
        self::assertEquals(
            EagerSolver::using(VisitedNodeSkipperFactory::using(
                BreadthFirstStrategyFactory::make()
            )),
            UniversalSolver::aimingTo(Find::aBestSolution())->select()
        );
    }

    /** @test */
    function picking_the_lazy_maze_puzzle_solver()
    {
        self::assertEquals(
            LazySolver::using(DuplicateNodeSkipperFactory::using(
                DepthFirstStrategyFactory::make()
            )),
            UniversalSolver::aimingTo(Find::allLooplessSolutions())->select()
        );
    }

    /** @test */
    function picking_the_network_navigation_solver()
    {
        self::assertEquals(
            EagerSolver::using(
                VisitedNodeCostCheckerFactory::using(
                    BestFirstStrategyFactory::noHeuristic()
                )
            ),
            UniversalSolver::aimingTo(Find::aBestSolution())
                ->withWeightedMoves()
                ->select()
        );
    }

    /** @test */
    function picking_the_n_queens_puzzle_solver()
    {
        self::assertEquals(
            LazySolver::using(VisitedNodeSkipperFactory::using(
                DepthFirstStrategyFactory::make()
            )),
            UniversalSolver::forAPuzzle()
                ->withGoalTo(Find::allBestSolutions())
                ->whereMovesEventuallyRunOut()
                ->select()
        );
    }

    /** @test */
    function picking_the_sliding_crates_puzzle_solver()
    {
        self::assertEquals(
            EagerSolver::using(VisitedNodeCostCheckerFactory::using(
                BestFirstStrategyFactory::withHeuristic(new CrateHeuristic())
            )),
            UniversalSolver::aimingTo(Find::aBestSolution())
                ->withHeuristic(new CrateHeuristic())
                ->select()
        );
    }

    /** @test */
    function picking_the_sliding_puzzle_solver()
    {
        self::assertEquals(
            EagerSolver::using(VisitedNodeCostCheckerFactory::using(
                BestFirstStrategyFactory::withHeuristic(
                    new LevenshteinHeuristic()
                )
            )),
            UniversalSolver::aimingTo(Find::aBestSolution())
                ->withHeuristic(new LevenshteinHeuristic())
                ->select()
        );
    }

    /** @test */
    function picking_the_sudoku_puzzle_solver()
    {
        self::assertEquals(
            EagerSolver::using(
                VisitedNodeSkipperFactory::using(
                    DepthFirstStrategyFactory::make()
                )
            ),
            UniversalSolver::forAPuzzle()
                ->withGoalTo(Find::theOnlySolution())
                ->whereMovesEventuallyRunOut()
                ->select()
        );
    }

    /** @test */
    function picking_the_wolf_goat_cabbage_puzzle_solver()
    {
        self::assertEquals(
            LazySolver::using(WorseThanBestSolutionSkipperFactory::using(
                BreadthFirstStrategyFactory::make()
            )),
            UniversalSolver::forAPuzzle()
                ->withGoalTo(Find::allBestSolutions())
                ->select()
        );
    }
}
