# Wolf-goat-cabbage Puzzle

The wolf, goat and cabbage problem is a river crossing puzzle. It dates back to 
at least the 9th century, and has entered the folklore of a number of ethnic 
groups.

## Story

Once upon a time a farmer went to a market and purchased a wolf, a goat, and a 
cabbage. 
On his way home, the farmer came to the bank of a river and rented a boat. 
But crossing the river by boat, the farmer could carry only himself and a 
single one of his purchases: the wolf, the goat, or the cabbage.

If left unattended together, the wolf would eat the goat, or the goat would eat 
the cabbage.
The farmer's challenge was to carry himself and his purchases to the far bank 
of the river, leaving each purchase intact. How did he do it? 

([source](https://en.wikipedia.org/wiki/Wolf,_goat_and_cabbage_problem))

## Solutions

There are two solutions to the problem, both take a total of 7 crossings.

- Take the goat over
- Return
- Take the wolf or cabbage over
- Return with the goat
- Take the cabbage or wolf over
- Return
- Take goat over

## Solver

### When looking for o best solution

| Puzzle attribute  | Value             |
|-------------------|-------------------|
| Goal              | A best solution   |
| Move weight       | Constant          |
| Heuristic         | No                |
| Exhausting*       | No                |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The wolf-goat-cabbage puzzle is not exhausting, 
because one could potentially move the same purchase back-and-forth forever.

Based on these attributes, the universal puzzle solver should pick an `eager` 
solver with `breadth first` search strategy and a `visited node skipping` 
decorator.

### When looking for all best solutions

| Puzzle attribute      | Value             |
|-----------------------|-------------------|
| Goal                  | All best solutions|
| Best paths converge   | Yes               |
| Move weight           | Constant          |
| Heuristic             | No                |
| Exhausting*           | No                |

Based on these attributes, the universal puzzle solver should pick a `lazy` 
solver with `breadth first` search strategy and a `worse than best solution 
skipping` decorator.
