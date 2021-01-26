<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\Puzzle\Sudoku\SudokuFactory;
use Stratadox\PuzzleSolver\UniversalSolver;

/**
 * @testdox Solving sudoku puzzles, depth first
 */
class Solving_sudoku_puzzles_depth_first extends TestCase
{
    /** @test */
    function eagerly_solving_a_sudoku_depth_first()
    {
        $solver = UniversalSolver::forAPuzzle()
            ->withGoalTo(Find::theOnlySolution())
            ->whereMovesEventuallyRunOut()
            ->select();
        $puzzle = SudokuFactory::make()->fromString('
            +-------+-------+-------+
            | 5 3 . | . 7 . | . . . |
            | 6 . . | 1 9 5 | . . . |
            | . 9 8 | . . . | . 6 . |
            +-------+-------+-------+
            | 8 . . | . 6 . | . . 3 |
            | 4 . . | 8 . 3 | . . 1 |
            | 7 . . | . 2 . | . . 6 |
            +-------+-------+-------+
            | . 6 . | . . . | 2 8 . |
            | . . . | 4 1 9 | . . 5 |
            | . . . | . 8 . | . 7 9 |
            +-------+-------+-------+
        ');

        $solution = $solver->solve($puzzle)[0];

        self::assertCount(51, $solution->moves());
        self::assertEquals(
            SudokuFactory::make()->fromString('
                +-------+-------+-------+
                | 5 3 4 | 6 7 8 | 9 1 2 |
                | 6 7 2 | 1 9 5 | 3 4 8 |
                | 1 9 8 | 3 4 2 | 5 6 7 |
                | 8 5 9 | 7 6 1 | 4 2 3 |
                | 4 2 6 | 8 5 3 | 7 9 1 |
                | 7 1 3 | 9 2 4 | 8 5 6 |
                | 9 6 1 | 5 3 7 | 2 8 4 |
                | 2 8 7 | 4 1 9 | 6 3 5 |
                | 3 4 5 | 2 8 6 | 1 7 9 |
                +-------+-------+-------+
            ')->representation(),
            $solution->state()->representation()
        );
    }

    /** @test */
    function not_solving_impossible_sudoku_puzzles()
    {
        $solver = UniversalSolver::forAPuzzle()
            ->withGoalTo(Find::theOnlySolution())
            ->whereMovesEventuallyRunOut()
            ->select();
        $puzzle = SudokuFactory::make()->fromString('
            +-------+-------+-------+
            | 5 3 . | . 7 . | 4 . . |
            | 6 . . | 1 9 5 | . . . |
            | . 9 8 | . . . | . 6 . |
            +-------+-------+-------+
            | 8 . . | . 6 . | . . 3 |
            | 4 . . | 8 . 3 | . . 1 |
            | 7 . . | . 2 . | . . 6 |
            +-------+-------+-------+
            | . 6 . | . . . | 2 8 . |
            | . . . | 4 1 9 | . . 5 |
            | . . . | . 8 . | . 7 9 |
            +-------+-------+-------+
        ');

        $this->expectException(NoSolution::class);

        $solver->solve($puzzle);
    }
}
