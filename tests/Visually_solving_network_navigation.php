<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\NetworkNavigation\NetworkNavigationPuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;
use function file_get_contents;
use function file_put_contents;
use function unlink;

/**
 * @testdox Visually solving network navigation
 */
class Visually_solving_network_navigation extends TestCase
{
    /** @test */
    function solving_the_puzzle_with_debug_output()
    {
        $fileName = __DIR__ . '/temp.txt';
        file_put_contents($fileName, '');

        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withWeightedMoves()
            ->withLogging($fileName, '; ')
            ->select();

        $puzzle = NetworkNavigationPuzzle::fromJsonAndStartAndGoal('
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
        ', 'A', 'C');

        $solver->solve($puzzle)[0];

        $output = file_get_contents($fileName);
        unlink($fileName);

        self::assertEquals('A; B; C; ', $output);
    }
}
