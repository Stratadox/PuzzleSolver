<?php declare(strict_types=1);

use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\Puzzle\Sudoku\SudokuPuzzle;
use Stratadox\PuzzleSolver\SearchStrategy\DebugLoggerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeSkipperFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;

require dirname(__DIR__) . '/vendor/autoload.php';

$solver = EagerSolver::using(
    VisitedNodeSkipperFactory::using(
        DebugLoggerFactory::withTimeout(
            80000,
            DepthFirstStrategyFactory::make()
        )
    )
);
$puzzle = SudokuPuzzle::fromArrays(
    [   5,    3, null, null,    7, null, null, null, null],
    [   6, null, null,    1,    9,    5, null, null, null],
    [null,    9,    8, null, null, null, null,    6, null],
    [   8, null, null, null,    6, null, null, null,    3],
    [   4, null, null,    8, null,    3, null, null,    1],
    [   7, null, null, null,    2, null, null, null,    6],
    [null,    6, null, null, null, null,    2,    8, null],
    [null, null, null,    4,    1,    9, null, null,    5],
    [null, null, null, null,    8, null, null,    7,    9]
);

echo "Starting!!\n\n{$puzzle->representation()}\n";

try {
    $solution = $solver->solve($puzzle)[0];
    echo "\n\n\nSOLVED\n\n\n";
} catch (NoSolution $e) {
    echo $e->getMessage();
}
