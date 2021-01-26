# Sliding Crates Puzzle

The objective of the puzzle is to move a target crate to the exit, a particular 
side of the board, by sliding the other crates out of the way until the way is 
free to slide the target crate to its goal.
Crates may be constrained in their movement directions.

## Puzzle input

The input could be a text-based level description like so:
```
. . . . A A 
. . B B C C 
r r . . E F 
G G H H E F 
. . . I E F 
. . . I J J 
```

In this input A through J represent the blocking rectangles and rr is the target 
rectangle that needs to be freed. By default, the exit is on the left side of 
the board.

## Puzzle output

Output should be a sequence of moves like A-up, B-down, etc.

Valid output for the example would be:

```
r right
r right
B left
A left
A left
C left
F up
F up
B left
C left
E up
E up
H right
H right
I up
J left
J left
J left
I down
H left
F down
F down
H left
E down
E down
E down
r right
F down
r right
```

For the example input, the solution can be found in 29 steps.
The final state of the slider puzzle would look like this:

```
. . A A . . 
B B C C . . 
. . . . r r 
G G H H E F 
. . . I E F 
. J J I E F 
```

## Solver

| Puzzle attribute  | Value             |
|-------------------|-------------------|
| Goal              | A best solution   |
| Move weight       | Constant          |
| Heuristic         | Yes               |
| Exhausting*       | No                |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The sliding crates puzzle is not exhausting, 
because one could potentially move the same crate back and forth forever.

Based on these attributes, the universal puzzle solver should pick an `eager` 
solver with `best first` search strategy with `heuristic` and a 
`visited node cost checker` decorator. This combination is also known as the 
[A* search algorithm](https://en.wikipedia.org/wiki/A*_search_algorithm).
