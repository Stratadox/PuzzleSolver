# Sudoku

Sudoku (originally called Number Place) is a logic-based, combinatorial 
number-placement puzzle. 
In classic sudoku, the objective is to fill a 9×9 grid with digits so that each 
column, each row, and each of the nine 3×3 subgrids that compose the grid (also 
called "boxes", "blocks", or "regions") contain all of the digits from 1 to 9. 
The puzzle setter provides a partially completed grid, which for a well-posed 
puzzle has a single solution.

([source](https://en.wikipedia.org/wiki/Sudoku))

## Sudoku input

The input for the sudoku puzzle is a list of arrays that contain either an 
integer between 1 and 9 or a `null` value.

## Sudoku output

As output, the sudoku solution contains a solved state and a list of number 
placements.

## Solver

It is highly recommended to use a depth-first puzzle solver. 
This sudoku puzzle implementation comes in both a mutable and an immutable 
flavour, either a stack-based or a recursive solver can be used.

| Puzzle attribute  | Value             |
|: ---------------- |: ---------------- |
| Goal              | The only solution |
| Move weight       | Constant          |
| Heuristic         | No                |
| Exhausting*       | Yes               |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The sudoku puzzle is exhausting, because after 
having placed several numbers, the available (valid) spaces to place new numbers 
eventually reaches zero.

Based on these attributes, the universal puzzle solver should pick a `lazy` 
solver with `depth first` search strategy and a `visited node skipping` 
decorator.
