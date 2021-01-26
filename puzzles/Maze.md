# Maze Puzzle

A simple maze puzzle. The hero can move to the left, right, up or down - but not 
through walls. The puzzle is solved by having the hero move to the goal, 
avoiding the obstacles.

## Maze input

The input for the maze could be text based, such as:
```
###################
# H    #     #X   #
# ###### # ###### #
#        #        #
###################
```
In the above example, the `#` marks a wall, `H` is for the hero and `X` is the 
goal, while spaces are open spaces.

## Maze output

The output for the maze would be a series of actions, such as `move left`, 
`move up`, etc.

## Solver

### Single path

Any puzzle solver can be used to find a path through a maze, but if the maze has 
multiple possible solutions, an eager depth-first solver might find a very odd 
and inefficient path.

| Puzzle attribute  | Value             |
|: ---------------- |: ---------------- |
| Goal              | A best solution   |
| Move weight       | Constant          |
| Heuristic         | No                |
| Exhausting*       | No                |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The maze puzzle is not exhausting, because one 
could potentially run around in circles forever.

Based on these attributes, the universal puzzle solver should pick an `eager` 
solver with `breadth first` search strategy and a `visited node skipping` 
decorator.

### All paths

| Puzzle attribute      | Value                     |
|: -------------------- |: ------------------------ |
| Goal                  | All loopless solutions    |
| Best paths converge   | Yes                       |
| Move weight           | Constant                  |
| Heuristic             | No                        |
| Exhausting*           | No                        |

Based on these attributes, the universal puzzle solver should pick a `lazy` 
solver with `breadth first` search strategy and a `duplicate move skipping` 
decorator.
