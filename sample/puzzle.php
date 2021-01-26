<?php declare(strict_types=1);

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazeFactory;
use Stratadox\PuzzleSolver\Puzzle\NQueens\NQueensPuzzle;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\CrateHeuristic;
use Stratadox\PuzzleSolver\Puzzle\SlidingCrates\SlidingCratesPuzzle;
use Stratadox\PuzzleSolver\SearchStrategy\BestFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\BreadthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DebugLoggerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeCostCheckerFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeSkipperFactory;
use Stratadox\PuzzleSolver\Solutions;
use Stratadox\PuzzleSolver\Solver\EagerSolver;
use Stratadox\PuzzleSolver\Solver\LazySolver;

require dirname(__DIR__) . '/vendor/autoload.php';

const EAGER_BFS = 'eager breadth first';
const CRATE = 'crate';
const LAZY_DFS = 'lazy depth first';

$timeout = 10000;
$solver = [
    EAGER_BFS => EagerSolver::using(
        VisitedNodeSkipperFactory::using(
            DebugLoggerFactory::withTimeout(
                $timeout,
                BreadthFirstStrategyFactory::make()
            )
        )
    ),
    CRATE => EagerSolver::using(
        VisitedNodeCostCheckerFactory::using(
            DebugLoggerFactory::withTimeout(
                $timeout,
                BestFirstStrategyFactory::withHeuristic(
                    new CrateHeuristic()
                )
            )
        )
    ),
    LAZY_DFS => LazySolver::using(
        VisitedNodeSkipperFactory::using(
            DebugLoggerFactory::withTimeout(
                $timeout,
                DepthFirstStrategyFactory::make()
            )
        )
    ),
];
$puzzles = [
    'maze',
    'n-queens',
    'sliding-crates',
    'n-sliding',
    'sudoku',
    'wolf-goat-cabbage',
];
echo "Choose your puzzle:\n";
foreach ($puzzles as $n => $name) {
    echo "$n: $name puzzle\n";
}
$n = readline("Type the number of your puzzle and press enter: ");
$f = str_replace('-', '_', $puzzles[$n]);
$puzzleName = $puzzles[$n] . ' puzzle';

echo "\n\n\n";
echo "Let's automatically solve a $puzzleName!\n\n";

if (!function_exists($f)) {
    echo "@todo: $f()\n";
    exit;
}

try {
    /** @var Solutions $solutions */
    $solutions = $f($solver);
} catch (NoSolution $exception) {
    die("FAILED\n\n{$exception->getMessage()}");
}

$n = count($solutions);
if ($n === 1) {
    echo "\n\nFound a solution!";
} else {
    echo "\n\nFound $n solutions!";
}

foreach ($solutions as $i => $solution) {
    $x = $i + 1;
    $puzzle = $solution->original();
    foreach ($solution->moves() as $move) {
        /** @var Puzzle $puzzle */
        $puzzle = $puzzle->afterMaking($move);
        echo str_repeat(PHP_EOL, 20) . "Solution $x:\n" . $puzzle->representation();
        usleep(200000);
    }
    usleep(300000);
}


/**
 * @param PuzzleSolver[] $solvers
 * @return Solutions
 * @throws NoSolution
 */
function maze(array $solvers): Solutions
{
    $mazes = [
        '
###################
# H    #          #
#            #  X #
###################
        ',
        '
###################
# H    #     #X   #
# ###### # ###### #
#        #        #
###################
        ',
        '
###################
#H# X             #
# ############### #
#      # #      # #
# ###### # ###### #
#                 #
###################
        ',
        '
#########
#H      #
# ##### #
# #X    #
# # #####
#       #
# ##### #
#       #
#########
        '
    ];

    echo "Choose your maze:\n";
    foreach ($mazes as $n => $name) {
        echo "$n:\n\n$name\n\n---\n\n";
    }
    $n = readline("Type the number of your maze and press enter: ");

    $maze = MazeFactory::default()->fromString($mazes[$n]);

    return $solvers[EAGER_BFS]->solve($maze);
}

/**
 * @param PuzzleSolver[] $solvers
 * @return Solutions
 * @throws NoSolution
 */
function n_queens(array $solvers): Solutions
{
    $nQueens = NQueensPuzzle::forQueens((int) readline('How many queens?'));

    return $solvers[LAZY_DFS]->solve($nQueens);
}

/**
 * @param PuzzleSolver[] $solvers
 * @return Solutions
 * @throws NoSolution
 */
function sliding_crates(array $solvers): Solutions
{
    echo "Enter the level:\n";
    $crates = '';
    do {
        $line = readline();
        $crates .= "$line\n";
    } while(!empty($line));
    $crate = readline('Which crate to liberate?');
    $cratePuzzle = SlidingCratesPuzzle::fromString($crates, $crate);

    return $solvers[CRATE]->solve($cratePuzzle);
}
