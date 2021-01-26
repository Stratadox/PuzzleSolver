<?php declare(strict_types=1);

use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\CrateHeuristic;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\SlidingCratesPuzzle;
use Stratadox\PuzzleSolver\SearchStrategy\BestFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DebugLoggerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeCostCheckerFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;

require dirname(__DIR__) . '/vendor/autoload.php';

$solver = EagerSolver::using(
    VisitedNodeCostCheckerFactory::using(
        DebugLoggerFactory::withTimeout(
            80000,
            BestFirstStrategyFactory::withHeuristic(
                new CrateHeuristic()
            )
        )
    )
);

$puzzle = SlidingCratesPuzzle::fromString('
    . . . . A A
    . . B B C C
    r r . . E F
    G G H H E F
    . . . I E F
    . . . I J J
', 'r');

echo "Starting...\n\n{$puzzle->representation()}\n\n";
usleep(500000);

$solution = $solver->solve($puzzle)[0];

try {
    $solution = $solver->solve($puzzle)[0];
    echo "\n\n\nSOLVED\n\n\n";
} catch (NoSolution $e) {
    echo $e->getMessage();
    exit;
}
usleep(2000000);

foreach ($solution->moves() as $move) {
    $puzzle = $puzzle->afterMaking($move);
    echo str_repeat(PHP_EOL, 20), $puzzle->representation(), PHP_EOL;
    usleep(600000);
}
