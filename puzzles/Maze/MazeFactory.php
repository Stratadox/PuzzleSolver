<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Maze;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;
use function assert;
use function preg_split;
use function str_split;

final class MazeFactory implements PuzzleFactory
{
    /** @var string */
    private $wall;
    /** @var string */
    private $goal;
    /** @var string */
    private $hero;

    public function __construct(string $wall, string $goal, string $hero)
    {
        $this->wall = $wall;
        $this->goal = $goal;
        $this->hero = $hero;
    }

    public static function default(): self
    {
        return new self('#', 'X', 'H');
    }

    public function fromString(string $maze): Puzzle
    {
        $walls = [];
        $goal = null;
        $hero = null;
        foreach (preg_split("/(\n)|(\r)/", $maze) as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                if ($char === $this->wall) {
                    $walls[] = new Wall($x, $y);
                }
                if ($char === $this->goal) {
                    $goal = new Goal($x, $y);
                }
                if ($char === $this->hero) {
                    $hero = new Hero($x, $y);
                }
            }
        }
        assert(null !== $goal);
        assert(null !== $hero);
        return MazePuzzle::withHeroAndMaze($hero, Maze::withGoalAndWalls($goal, ...$walls));
    }
}
