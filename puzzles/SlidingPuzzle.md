# Sliding Puzzle

The sliding puzzle is a combination puzzle that challenges 
a player to slide pieces along on a board to establish the 
goal position.

## Puzzle input

The input can consist of a 2d array of numbers, for instance:
```php
[
    [2, 4, 1],
    [8, 5, 7],
    [3, 0, 6],
];
```
0 is considered to be the open space.

## Puzzle output

The output of the puzzle is a series of sliding maneuvers 
that result in a board state where the pieces are in a 
sequential order.

For example:
```
Slide 5 down
Slide 7 left
Slide 6 up
Slide 5 right
Slide 3 right
Slide 8 down
Slide 7 left
Slide 3 up
Slide 5 left
Slide 6 down
Slide 3 right
Slide 5 up
Slide 8 right
Slide 7 down
Slide 2 down
Slide 4 left
Slide 1 left
Slide 3 up
Slide 5 right
Slide 2 right
Slide 4 down
Slide 1 left
Slide 2 up
Slide 5 right
Slide 6 up
```

## Solver

Any solver would probably end up solving this puzzle. 
Although depth-first searchers might come up with an 
inefficient answer, all search strategies can be used to 
find a valid answer.

Since we can use a heuristic, e.g. the [Levenshtein distance](https://en.wikipedia.org/wiki/Levenshtein_distance)
between the current state and the goal state, an [A* search](https://en.wikipedia.org/wiki/A*_search_algorithm) 
can be used to find the shortest answer most efficiently.

| Puzzle attribute  | Value             |
|-------------------|-------------------|
| Goal              | A best solution   |
| Move weight       | Constant          |
| Heuristic         | Yes               |
| Exhausting*       | No                |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The sliding puzzle is not exhausting, because 
one could potentially move the same tile back and forth forever.

Based on these attributes, the universal puzzle solver should pick an `eager` 
solver with `best first` search strategy with `heuristic` and a 
`visited node cost checker` decorator. This combination is also known as the 
[A* search algorithm](https://en.wikipedia.org/wiki/A*_search_algorithm).
