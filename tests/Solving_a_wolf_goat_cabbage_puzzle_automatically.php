<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage\RiverCrossingFactory;
use Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage\RiverCrossingPuzzle;
use Stratadox\PuzzleSolver\SearchStrategy\BreadthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\IterationLimiterFactory;
use Stratadox\PuzzleSolver\SearchStrategy\OutOfIterations;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeSkipperFactory;
use Stratadox\PuzzleSolver\SearchStrategy\WorseThanBestSolutionSkipperFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;
use Stratadox\PuzzleSolver\Solver\LazySolver;

/**
 * @testdox Solving a wolf-goat-cabbage puzzle, automatically
 */
class Solving_a_wolf_goat_cabbage_puzzle_automatically extends TestCase
{
    /** @test */
    function eagerly_finding_the_crossings_breadth_first()
    {
        $solver = EagerSolver::using(VisitedNodeSkipperFactory::using(
            BreadthFirstStrategyFactory::make()
        ));
        $puzzle = RiverCrossingFactory::make()->fromString('Foo de la bar');

        $solution = $solver->solve($puzzle)[0];

        self::assertCount(7, $solution->moves());
    }

    /** @test */
    function lazily_finding_the_crossings_breadth_first()
    {
        $solver = LazySolver::using(WorseThanBestSolutionSkipperFactory::using(
            BreadthFirstStrategyFactory::make()
        ));
        $puzzle = RiverCrossingPuzzle::begin();

        $solutions = $solver->solve($puzzle);

        self::assertCount(2, $solutions);
        foreach ($solutions as $solution) {
            self::assertCount(7, $solution->moves());
        }
    }

    /** @test */
    function running_out_of_iterations_when_lazily_solving_depth_first()
    {
        $solver = LazySolver::using(
            WorseThanBestSolutionSkipperFactory::using(
                IterationLimiterFactory::limitingTo(
                    100,
                    DepthFirstStrategyFactory::make()
                )
            )
        );
        $puzzle = RiverCrossingPuzzle::begin();

        $this->expectException(OutOfIterations::class);
        $solver->solve($puzzle);
    }
}
