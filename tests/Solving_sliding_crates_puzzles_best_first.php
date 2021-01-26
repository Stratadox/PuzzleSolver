<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\CrateHeuristic;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\SlidingCratesPuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;
use function implode;

/**
 * @testdox Solving sliding crates puzzles, best first
 */
class Solving_sliding_crates_puzzles_best_first extends TestCase
{
    /** @test */
    function solving_a_simple_sliding_crates_puzzle()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withHeuristic(new CrateHeuristic())
            ->select();

        $puzzle = SlidingCratesPuzzle::fromString('
            . . A A A 
            r r . . B 
            . . . . B 
            . D . . B 
            . D C C C 
        ', 'r');

        $solution = $solver->solve($puzzle)[0];

        self::assertEquals(6, $solution->cost(), implode("\n", $solution->moves()->items()));
    }

    /**
     * @test
     * @group thorough
     */
    function solving_the_puzzle_from_the_example()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withHeuristic(new CrateHeuristic())
            ->select();

        $puzzle = SlidingCratesPuzzle::fromString('
            . . . . A A 
            . . B B C C 
            r r . . E F 
            G G H H E F 
            . . . I E F 
            . . . I J J 
        ', 'r');

        $solution = $solver->solve($puzzle)[0];

        self::assertEquals(29, $solution->cost(), implode("\n", $solution->moves()->items()));
    }
}
