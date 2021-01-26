<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle\LevenshteinHeuristic;
use Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle\SlidingPuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;

/**
 * @testdox Solving "regular" sliding puzzles, best first
 */
class Solving_regular_sliding_puzzles_best_first extends TestCase
{
    /** @test */
    function solving_a_simple_sliding_puzzle()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withHeuristic(new LevenshteinHeuristic())
            ->select();

        $puzzle = SlidingPuzzle::withPieces(
            [2, 3],
            [1, 0]
        );

        $solution = $solver->solve($puzzle)[0];

        self::assertEquals(4, $solution->cost());
        self::assertEquals('Slide 3 down', $solution->moves()[0]);
        self::assertEquals('Slide 2 right', $solution->moves()[1]);
        self::assertEquals('Slide 1 up', $solution->moves()[2]);
        self::assertEquals('Slide 3 left', $solution->moves()[3]);
    }

    /** @test */
    function solving_the_sliding_puzzle_from_the_example()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withHeuristic(new LevenshteinHeuristic())
            ->select();

        $puzzle = SlidingPuzzle::withPieces(
            [2, 4, 1],
            [8, 5, 7],
            [3, 0, 6]
        );

        $solution = $solver->solve($puzzle)[0];

        self::assertEquals(25, $solution->cost());
    }
}
