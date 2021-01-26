# N-Queens Puzzle

The n-queens puzzle is the problem of placing n chess queens on an n×n 
chessboard so that no two queens threaten each other; thus, a solution requires 
that no two queens share the same row, column, or diagonal.

## Puzzle input

Only a single integer is required as puzzle input: this number signifies both 
the size of the board and the number of queens to place on it.

## Puzzle output

The n-queens puzzle can typically be solved in various ways. Depending on the 
type of solver, one or all solutions can be found.
A single solution consists of `n` queen placements.

## Solver

In order to find *all* possible solutions, a `lazy` solver is required. 
When only a single solution is needed, an `eager` solver is faster.

| Puzzle attribute      | Value             |
|: ----------------     |: ---------------- |
| Goal                  | All best solutions|
| Best paths converge   | No                |
| Move weight           | Constant          |
| Heuristic             | No                |
| Exhausting*           | Yes               |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The n-queens puzzle is exhausting, because after 
having placed a number of queens, the available spaces to place new queens 
eventually reaches zero.

Based on these attributes, the universal puzzle solver should pick a `lazy` 
solver with `depth first` search strategy and a `visited node skipping` 
decorator.
