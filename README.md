# Puzzle Solver

![Github Action](https://github.com/Stratadox/PuzzleSolver/workflows/Github%20Action/badge.svg)
[![codecov](https://codecov.io/gh/Stratadox/PuzzleSolver/branch/main/graph/badge.svg)](https://codecov.io/gh/Stratadox/PuzzleSolver)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/PuzzleSolver/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Stratadox/PuzzleSolver/?branch=main)
[![Maintainability](https://api.codeclimate.com/v1/badges/d43d21a26591fb14cdc3/maintainability)](https://codeclimate.com/github/Stratadox/PuzzleSolver/maintainability)
[![Latest Stable Version](https://poser.pugx.org/stratadox/puzzle-solver/v/stable)](https://packagist.org/packages/stratadox/puzzle-solver)
[![License](https://poser.pugx.org/stratadox/puzzle-solver/license)](https://packagist.org/packages/stratadox/puzzle-solver)

A generic puzzle solving package, capable of solving a wide variety of puzzles.

## Examples

Some examples of puzzles it can solve:

- [Mazes](puzzles/Maze.md)
    - [Eagerly solving a maze](tests/Eagerly_solving_a_maze.php)
    - [Eagerly solving a maze, depth first](tests/Eagerly_solving_a_maze_depth_first.php)
    - [Lazily solving a maze, seeking all paths](tests/Lazily_solving_a_maze_seeking_all_paths.php)
- [The Wolf-Goat-Cabbage problem](puzzles/WolfGoatCabbage.md)
    - [Solving a wolf-goat-cabbage puzzle, automatically](tests/Solving_a_wolf_goat_cabbage_puzzle_automatically.php)
    - [Solving a wolf-goat-cabbage puzzle, universally](tests/Solving_a_wolf_goat_cabbage_puzzle_universally.php)
- [Network navigation puzzles](puzzles/NetworkNavigation.md)
    - [Solving network navigation, best first](tests/Solving_network_navigation_best_first.php)
- [N-Queens problem](puzzles/NQueens.md)
    - [Solving n-queens puzzles, depth first](tests/Solving_n_queens_puzzles_depth_first.php)
- [Sliding Crates puzzle](puzzles/SlidingCrates.md)
    - [Solving sliding crates puzzles, best first](tests/Solving_sliding_crates_puzzles_best_first.php)
- [Sliding Puzzles](puzzles/SlidingPuzzle.md)
    - [Solving "regular" sliding puzzles, best first](tests/Solving_regular_sliding_puzzles_best_first.php)
- [Sudoku puzzles](puzzles/Sudoku.md)
    - [Solving sudoku puzzles, depth first](tests/Solving_sudoku_puzzles_depth_first.php)

## Motivation

People kept asking me to implement a solver for this puzzle or that puzzle. 
This time, instead of just solving one puzzle, I figured I'd make a universal 
puzzle solver that would simply solve **all** of those puzzles and all puzzles 
to come, in one swift strike.
Plus, this way, the algorithms can be re-used for different purposes, such as 
AI for video games or a solver for some real-life problem. 
Who knows what the future brings!

## Basic usage

There are two "ways" to use this package: the Universal Solver, an informed 
"swiss army knife" approach, or the Brute Solver... what's in a name.

### Universal Solver

The Universal Solver is like a swiss army knife for puzzle solvers.

Feed it with some information on what you're looking for, and receive a solver 
for your puzzle. All the puzzles that are implemented as examples, as well as 
many puzzles that don't have an example implementation, can be given efficient 
solvers using this approach.

#### 5-queens problem with universal solver
Solving a 5-queens problem:
```php
<?php
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\NQueens\NQueensPuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;

$solver = UniversalSolver::forAPuzzle()
    ->withGoalTo(Find::allBestSolutions())
    ->whereMovesEventuallyRunOut()
    ->select();


$solutions = $solver->solve(NQueensPuzzle::forQueens(5));

assert(count($solutions) === 10);
```

#### Sliding puzzle with universal solver
Solving a sliding puzzle:
```php
<?php
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle\LevenshteinHeuristic;
use Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle\SlidingPuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;

$solver = UniversalSolver::aimingTo(Find::aBestSolution())
    ->withHeuristic(new LevenshteinHeuristic())
    ->select();

$puzzle = SlidingPuzzle::withPieces(
    [2, 4, 1],
    [8, 5, 7],
    [3, 0, 6]
);

$solution = $solver->solve($puzzle)[0];

assert(count($solution->moves()) === 25);
```

### Brute Solver

On the [to-do list](#to-do).

## Available Algorithms

Rather than providing a set number of algorithms, this package provides a 
**composable** algorithm.

The solver itself can be of one of two flavours, with one of three basic search 
strategies which in turn can be refined with a number of decorators.

### Flavours

The basic solver comes in two flavours: eager or lazy. 

#### Eager

Eager puzzle solvers will search for the first valid solution. Eager solvers
will stop after finding a single solution, making them neat for puzzles that
can have only one possible solution, or require only a single solution.

#### Lazy

Lazy puzzle solvers will continue to look for solutions until they've found
*all* valid solutions. Lazy solvers can be used for puzzles that require more
than one solution.

### Search Strategies

There are three main search strategies available: depth-first, breadth-first 
and best-first.

#### Depth-first

Depth-first searches continuously follow the first option, until it turns out
the branch does not lead to a solution, at which point it tracks back to the
first next available option.

#### Breadth-first

Breadth-first searches continuously explore all available options, before
passing into the options available after those.

#### Best-first

Best-first searches enqueue newly encountered nodes by order of quality. The
move that is most likely to lead to the desired outcome is considered first.
In order to determine the quality of a move, the best-first strategy can be 
provisioned with a [heuristic](https://en.wikipedia.org/wiki/Heuristic_(computer_science)).

### Decorators

In order to optimise the search, and/or to not get stuck in eternal loops, a 
search strategy will typically be wrapped in at least one decorator.

#### Visited Node Skipper

Visited Node Skipper keeps a list of puzzle states that have already been
encountered during the search to skip candidates when they are about to be 
considered for a second time.

#### Visited Node Cost Checker

Visited Node Cost Checker keeps a record of the cost of reaching each
considered candidate. When a candidate is encountered that already has a 
recorded cost, the cost of the newly discovered path to reach the same state 
as before is compared with the previously recorded cost. If the cost is lower 
than before, the record gets updated and the new candidate is considered. In 
case the cost is equal or higher than previous paths to the same goal, the 
candidate is discarded.

#### Worse-Than-Best Solution Skipper

The Worse-Than-Best Solution Skipper keeps track of the lowest cost of the
solutions that have been found during the search. Each time a node is
encountered of which the path cost is more than the cost of the cheapest 
solution that has already been found, the node is skipped.

#### Duplicate Node Skipper

Duplicate Node Skipper prevents paths from getting into loops.
Before considering a new candidate, all previous puzzle states are compared
with the new state. If the new puzzle state was already reached on this very 
path, the candidate is considered a loop and gets rejected.

#### Iteration Limiter

Iteration Limiter aborts the search when the amount of considered candidates 
exceeds a set limit.

#### Debug Logger

Debug Logger is a visualiser for the puzzle solver. Each time a new candidate
is taken under consideration, the debug logger logs it to the output stream.

## To-do

- A "Brute Solver": given an iteration limit, try *all** configurations of 
  solvers. 
  - Start with the lazy searches, then move to eager. 
  - Try a solver, catch OutOfIterations and attempt the next.
  
  (*) Maybe not **all**, but a bunch of sensible ones..

- A neat (and extensible) console app to solve puzzles with. The current 
  `samples` directory is a bit primitive.
  - PuzzleFactory interface and a factory per puzzle, to streamline loading 
    puzzle input and enhance extensibility.
  - SolutionRenderer 
  - Symfony console Application? (To decide: do we want that dependency in a 
    utility repo)

- Website version (Or rather, the link to a future repository for that)
  - Easy-to-use way to add new puzzles? (Can that be done?)
