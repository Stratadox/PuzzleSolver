<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Puzzle\Dummy\DummyPuzzle;
use Stratadox\PuzzleSolver\SearchStrategy\BreadthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DebugLoggerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;
use Stratadox\PuzzleSolver\Solver\LazySolver;

/**
 * @testdox Dummy test is already solved
 */
class Dummy_test_is_already_solved extends TestCase
{
    /** @test */
    function eagerly_solving_the_immutable_dummy_puzzle_breadth_first()
    {
        $solver = EagerSolver::using(BreadthFirstStrategyFactory::make());

        $solution = $solver->solve(new DummyPuzzle())[0];

        self::assertCount(0, $solution->moves());
        self::assertEquals(0, $solution->cost());
    }

    /** @test */
    function eagerly_solving_the_immutable_dummy_puzzle_depth_first()
    {
        $solver = EagerSolver::using(DepthFirstStrategyFactory::make());

        $solution = $solver->solve(new DummyPuzzle())[0];

        self::assertCount(0, $solution->moves());
        self::assertEquals(0, $solution->cost());
    }

    /** @test */
    function lazily_solving_the_mutable_dummy_puzzle_breadth_first()
    {
        $solver = LazySolver::using(BreadthFirstStrategyFactory::make());

        $solutions = $solver->solve(new DummyPuzzle());

        self::assertCount(1, $solutions);
        self::assertCount(0, $solutions[0]->moves());
        self::assertEquals(0, $solutions[0]->cost());
    }

    /** @test */
    function lazily_solving_the_mutable_dummy_puzzle_depth_first()
    {
        $solver = LazySolver::using(DepthFirstStrategyFactory::make());

        $solutions = $solver->solve(new DummyPuzzle());

        self::assertCount(1, $solutions);
        self::assertCount(0, $solutions[0]->moves());
        self::assertEquals(0, $solutions[0]->cost());
    }

    /** @test */
    function debug_logging_the_dummy()
    {
        $fileName = __DIR__ . '/temp.txt';
        file_put_contents($fileName, '');
        $solver = EagerSolver::using(
            DebugLoggerFactory::withTimeout(
                0,
                DepthFirstStrategyFactory::make(),
                $fileName
            )
        );

        $solver->solve(new DummyPuzzle());
        $output = file_get_contents($fileName);
        unlink($fileName);

        self::assertStringContainsString('Dummy', $output);
    }
}
