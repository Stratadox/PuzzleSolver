<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\NetworkNavigation\NetworkPuzzleFactory;
use Stratadox\PuzzleSolver\PuzzleCreationFailure;
use Stratadox\PuzzleSolver\UniversalSolver;

/**
 * @testdox Solving network navigation, best first
 */
class Solving_network_navigation_best_first extends TestCase
{
    /** @test */
    function finding_the_shortest_path_in_the_example_graph()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withWeightedMoves()
            ->select();
        $puzzle = NetworkPuzzleFactory::make()->fromString('
          {
            "edges": [
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
            ],
            "start": "A",
            "goal": "C"
          }
        ');

        $solution = $solver->solve($puzzle)[0];

        self::assertEquals(2.74, $solution->cost());
        self::assertCount(2, $solution->moves());
        self::assertEquals('Go to B', $solution->moves()[0]);
        self::assertEquals('Go to C', $solution->moves()[1]);
    }

    /** @test */
    function not_loading_invalid_json_input()
    {
        $factory = NetworkPuzzleFactory::make();

        $this->expectException(PuzzleCreationFailure::class);

        $factory->fromString('{ "edges": [{');
    }
}
