<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazeFactory;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazePuzzle;
use Stratadox\PuzzleSolver\UniversalSolver;
use function assert;

/**
 * @testdox Eagerly solving a maze
 */
class Eagerly_solving_a_maze extends TestCase
{
    /** @var PuzzleSolver */
    private $solver;
    /** @var MazeFactory */
    private $newMaze;

    protected function setUp(): void
    {
        $this->solver = UniversalSolver::aimingTo(Find::aBestSolution())->select();
        $this->newMaze = MazeFactory::make();
    }

    /** @test */
    function eagerly_solving_a_simple_maze()
    {
        $maze = $this->newMaze->fromString('
###################
# H             X #
###################
        ');

        $solution = $this->solver->solve($maze)[0];

        self::assertCount(14, $solution->moves());

        $solutionState = $solution->state();
        assert($solutionState instanceof MazePuzzle);
        $hero = $solutionState->hero();
        self::assertEquals(2, $hero->y());
        self::assertEquals(16, $hero->x());
    }

    /** @test */
    function eagerly_solving_a_maze_with_obstacles()
    {
        $maze = $this->newMaze->fromString('
###################
# H    #          #
#            #  X #
###################
        ');

        $solution = $this->solver->solve($maze)[0];

        self::assertCount(17, $solution->moves());

        $solutionState = $solution->state();
        assert($solutionState instanceof MazePuzzle);
        $hero = $solutionState->hero();
        self::assertEquals(3, $hero->y());
        self::assertEquals(16, $hero->x());
    }

    /** @test */
    function eagerly_solving_a_maze_with_multiple_obstacles()
    {
        $maze = $this->newMaze->fromString('
###################
# H    #     #X   #
# ###### # ###### #
#        #        #
###################
        ');

        $solution = $this->solver->solve($maze)[0];

        self::assertCount(28, $solution->moves());

        $solutionState = $solution->state();
        assert($solutionState instanceof MazePuzzle);
        $hero = $solutionState->hero();
        self::assertEquals(2, $hero->y());
        self::assertEquals(14, $hero->x());
    }

    /** @test */
    function not_eagerly_solving_unsolvable_puzzles()
    {
        $maze = $this->newMaze->fromString('
###################
# H     #       X #
###################
        ');

        $this->expectException(NoSolution::class);

        $this->solver->solve($maze);
    }

    /** @test */
    function unsolved_mazes_are_not_solved()
    {
        $maze = $this->newMaze->fromString('
###################
# H             X #
###################
        ');

        self::assertFalse($maze->isSolved());
    }
}
