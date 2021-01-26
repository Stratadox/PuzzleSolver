## Network Navigation Puzzle

On a network of nodes, the objective is to get the Target 
from it's initial node to the Goal node along the cheapest 
route.

## Puzzle input

The input to the puzzle might be a list of edges, like so:
```json
[
  {
    "from": "A",
    "to": "B",
    "cost": 1.4
  },
  {
    "from": "B",
    "to": "C",
    "cost": 1.34
  },
  {
    "from": "A",
    "to": "C",
    "cost": 2.76
  }
]
```
Together with a start and target node.

## Puzzle output

Puzzle output consists of a list of moves, such as:
```
Go to B
Go to C
```
As well as the total cost of the route, in this case `2.74`

## Solver

Since the *cheapest* route is sought, and each of the moves 
has a distinct cost, neither the default depth first or 
breadth-first algorithms, whether eager or lazy, are very 
suitable for solving this puzzle.

| Puzzle attribute  | Value             |
|: ---------------- |: ---------------- |
| Goal              | A best solution   |
| Move weight       | Variable          |
| Heuristic         | No                |
| Exhausting*       | No                |

*A puzzle is considered Exhausting if, by making moves, the options for future 
moves eventually reaches zero. The network navigation puzzle is not exhausting, 
because one could potentially move back and forth between nodes forever.

Based on these attributes, the universal puzzle solver should pick an `eager` 
solver with `best first` search strategy and a `visited node cost checker` 
decorator. This combination is also known as [Dijkstra's algorithm](https://en.wikipedia.org/wiki/Dijkstra%27s_algorithm).
