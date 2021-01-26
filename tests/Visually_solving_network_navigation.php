<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\NetworkNavigation\NetworkNavigationPuzzle;
use Stratadox\PuzzleSolver\Renderer\MovesToFileRenderer;
use Stratadox\PuzzleSolver\UniversalSolver;
use function file_get_contents;
use function file_put_contents;
use function unlink;

/**
 * @testdox Visually solving network navigation
 */
class Visually_solving_network_navigation extends TestCase
{
    private const FILE = __DIR__ . '/temp.txt';
    private const NETWORK = '[
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
    ]';

    protected function setUp(): void
    {
        file_put_contents(self::FILE, '');
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::FILE);
    }

    /** @test */
    function solving_the_puzzle_with_debug_output()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withWeightedMoves()
            ->withLogging(self::FILE, '; ')
            ->select();

        $puzzle = NetworkNavigationPuzzle::fromJsonAndStartAndGoal(self::NETWORK, 'A', 'C');

        $solver->solve($puzzle)[0];

        $output = file_get_contents(self::FILE);

        self::assertEquals('A; B; C', $output);
    }

    /** @test */
    function playing_back_the_solution()
    {
        $solver = UniversalSolver::aimingTo(Find::aBestSolution())
            ->withWeightedMoves()
            ->select();

        $renderer = MovesToFileRenderer::fromFilenameAndSeparator(self::FILE, '; ');

        $puzzle = NetworkNavigationPuzzle::fromJsonAndStartAndGoal(self::NETWORK, 'A', 'C');

        $solution = $solver->solve($puzzle)[0];

        $renderer->render($solution);

        $output = file_get_contents(self::FILE);

        self::assertEquals('Go to B; Go to C', $output);
    }
}
