<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage\RiverCrossingPuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;

/**
 * @testdox Solving a wolf-goat-cabbage puzzle, universally
 */
class Solving_a_wolf_goat_cabbage_puzzle_universally extends TestCase
{
    /** @test */
    function looking_for_just_one_solution()
    {
        $solver = UniversalSolver::forAPuzzle()
            ->withGoalTo(Find::aBestSolution())
            ->select();

        $puzzle = RiverCrossingPuzzle::begin();

        $solutions = $solver->solve($puzzle);

        self::assertCount(1, $solutions);
        foreach ($solutions as $solution) {
            self::assertCount(7, $solution->moves());
        }
    }

    /** @test */
    function looking_for_both_solutions()
    {
        $solver = UniversalSolver::forAPuzzle()
            ->withGoalTo(Find::allBestSolutions())
            ->select();

        $puzzle = RiverCrossingPuzzle::begin();

        $solutions = $solver->solve($puzzle);

        self::assertCount(2, $solutions);
        foreach ($solutions as $solution) {
            self::assertCount(7, $solution->moves());
        }
    }
}
