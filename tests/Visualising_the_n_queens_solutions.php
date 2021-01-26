<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\NQueens\NQueensFactory;
use Stratadox\PuzzleSolver\Puzzle\NQueens\NQueensPuzzle;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\Renderer\MovesToFileRenderer;
use Stratadox\PuzzleSolver\Renderer\PuzzleStatesToFileRenderer;
use Stratadox\PuzzleSolver\UniversalSolver;
use function explode;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function substr_count;
use function unlink;

/**
 * @testdox Visualising the n-queens solutions
 */
class Visualising_the_n_queens_solutions extends TestCase
{
    private const FILE = __DIR__ . '/temp.txt';
    /** @var PuzzleSolver */
    private $solver;

    protected function setUp(): void
    {
        $this->solver = UniversalSolver::forAPuzzle()
            ->withGoalTo(Find::allBestSolutions())
            ->whereMovesEventuallyRunOut()
            ->select();
        file_put_contents(self::FILE, '');
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::FILE);
    }

    /** @test */
    function visualising_the_winning_moves_of_the_first_solution()
    {
        $solution = $this->solver->solve(NQueensFactory::make()->fromString('5'))[0];
        $renderer = MovesToFileRenderer::fromFilenameAndSeparator(self::FILE, '; ');

        $renderer->render($solution);

        $output = file_get_contents(self::FILE);

        self::assertEquals(
            implode('; ', $solution->moves()->items()),
            $output
        );
    }

    /** @test */
    function visualising_the_winning_states_of_the_first_solution()
    {
        $solution = $this->solver->solve(NQueensPuzzle::forQueens(5))[0];
        $renderer = PuzzleStatesToFileRenderer::fromFilenameAndSeparator(self::FILE, ';');

        $renderer->render($solution);

        $output = file_get_contents(self::FILE);
        $frames = explode(';', $output);
        self::assertCount(1 + 5, $frames);
        foreach ($frames as $i => $frame) {
            // Each frame adds one `Q`
            self::assertEquals($i, substr_count($frame, 'Q'), $frame);
        }
    }
}
