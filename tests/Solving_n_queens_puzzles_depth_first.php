<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\NQueens\NQueensPuzzle;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\UniversalSolver;

/**
 * @testdox Solving n-queens puzzles, depth first
 */
class Solving_n_queens_puzzles_depth_first extends TestCase
{
    /** @var PuzzleSolver */
    private $solver;

    protected function setUp(): void
    {
        $this->solver = UniversalSolver::forAPuzzle()
            ->withGoalTo(Find::allBestSolutions())
            ->whereMovesEventuallyRunOut()
            ->select();
    }

    /** @test */
    function solving_the_5_queens_puzzle()
    {
        $solutions = $this->solver->solve(NQueensPuzzle::forQueens(5));

        self::assertCount(10, $solutions);
    }

    /** @test */
    function solving_the_6_queens_puzzle()
    {
        $solutions = $this->solver->solve(NQueensPuzzle::forQueens(6));

        self::assertCount(4, $solutions);
    }

    /** @test */
    function solving_the_7_queens_puzzle()
    {
        $solutions = $this->solver->solve(NQueensPuzzle::forQueens(7));

        self::assertCount(40, $solutions);
    }

    /**
     * @test
     * @group thorough
     */
    function solving_the_8_queens_puzzle()
    {
        $solutions = $this->solver->solve(NQueensPuzzle::forQueens(8));

        self::assertCount(92, $solutions);
    }

    /**
     * @test
     * @group thorough
     */
    function solving_the_9_queens_puzzle()
    {
        $solutions = $this->solver->solve(NQueensPuzzle::forQueens(9));

        self::assertCount(352, $solutions);
    }
}
