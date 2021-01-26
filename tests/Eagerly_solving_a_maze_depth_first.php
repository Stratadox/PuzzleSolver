<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\PuzzleSolver;
use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazeFactory;
use Stratadox\PuzzleSolver\Puzzle\Maze\MazePuzzle;
use Stratadox\PuzzleSolver\SearchStrategy\DepthFirstStrategyFactory;
use Stratadox\PuzzleSolver\SearchStrategy\VisitedNodeSkipperFactory;
use Stratadox\PuzzleSolver\Solver\EagerSolver;
use function assert;
use function count;

/**
 * @testdox Eagerly solving a maze, depth first
 */
class Eagerly_solving_a_maze_depth_first extends TestCase
{
    /** @var PuzzleSolver */
    private $solver;
    /** @var MazeFactory */
    private $newMaze;

    protected function setUp(): void
    {
        $this->solver = EagerSolver::using(
            VisitedNodeSkipperFactory::using(
                DepthFirstStrategyFactory::make()
            )
        );
        $this->newMaze = MazeFactory::default();
    }

    /** @test */
    function eagerly_solving_a_simple_maze_depth_first()
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
    function eagerly_solving_a_maze_with_obstacles_depth_first()
    {
        $maze = $this->newMaze->fromString('
###################
# H    #          #
#            #  X #
###################
        ');

        $solution = $this->solver->solve($maze)[0];

        // Depth-first searches are not very efficient for this type of puzzle
        self::assertGreaterThan(17, count($solution->moves()));

        $solutionState = $solution->state();
        assert($solutionState instanceof MazePuzzle);
        $hero = $solutionState->hero();
        self::assertEquals(3, $hero->y());
        self::assertEquals(16, $hero->x());
    }

    /** @test */
    function eagerly_solving_a_maze_with_multiple_obstacles_depth_first()
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
    function not_eagerly_solving_unsolvable_puzzles_depth_first()
    {
        $maze = $this->newMaze->fromString('
###################
# H     #       X #
###################
        ');

        $this->expectException(NoSolution::class);

        $this->solver->solve($maze);
    }
}
