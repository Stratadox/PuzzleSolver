<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazeFactory;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazePuzzle;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\UniversalSolver;
use function assert;

/**
 * @testdox Lazily solving a maze, seeking all paths
 */
class Lazily_solving_a_maze_seeking_all_paths extends TestCase
{
    /** @var PuzzleSolver */
    private $solver;
    /** @var MazeFactory */
    private $newMaze;

    protected function setUp(): void
    {
        $this->solver = UniversalSolver::aimingTo(Find::allLooplessSolutions())->select();
        $this->newMaze = MazeFactory::make();
    }

    /** @test */
    function lazily_solving_a_simple_maze()
    {
        $maze = $this->newMaze->fromString('
#################
#H             X#
#################
        ');

        $solutions = $this->solver->solve($maze);

        self::assertCount(1, $solutions);
        self::assertCount(14, $solutions[0]->moves());

        $solutionState = $solutions[0]->state();
        assert($solutionState instanceof MazePuzzle);
        $hero = $solutionState->hero();
        self::assertEquals(2, $hero->y());
        self::assertEquals(15, $hero->x());
    }

    /** @test */
    function lazily_solving_a_maze_with_two_solutions()
    {
        $maze = $this->newMaze->fromString('
####
#H #
# X#
####
        ');

        $solutions = $this->solver->solve($maze);

        self::assertCount(2, $solutions, (string) $solutions);
        foreach ($solutions as $solution) {
            self::assertEquals(2, $solution->cost());
        }
    }

    /**
     * @test
     * @group thorough
     */
    function lazily_solving_a_maze_with_three_loopless_solutions()
    {
        $maze = $this->newMaze->fromString('
#########
#H      #
# ##### #
# #X    #
# # #####
#       #
# ##### #
#       #
#########
        ');

        $solutions = $this->solver->solve($maze);

        self::assertCount(3, $solutions, (string) $solutions);
    }
}
