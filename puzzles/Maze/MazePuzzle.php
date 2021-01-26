<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Maze;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;
use function assert;
use function explode;
use function implode;
use const PHP_EOL;

final class MazePuzzle implements Puzzle
{
    /** @var Maze */
    private $maze;
    /** @var Hero */
    private $hero;
    /** @var Moves */
    private $actionsSoFar;

    public function __construct(Maze $maze, Hero $hero, Moves $actionsSoFar)
    {
        $this->maze = $maze;
        $this->hero = $hero;
        $this->actionsSoFar = $actionsSoFar;
    }

    public static function withHeroAndMaze(Hero $hero, Maze $maze): Puzzle
    {
        return new self($maze, $hero, Moves::none());
    }

    public function isSolved(): bool
    {
        return $this->maze->isCompletedBy($this->hero);
    }

    public function movesSoFar(): Moves
    {
        return $this->actionsSoFar;
    }

    public function representation(): string
    {
        $maze = explode(PHP_EOL, (string) $this->maze);
        $maze[$this->hero->y()][$this->hero->x()] = 'H';
        return implode(PHP_EOL, $maze);
    }

    public function possibleMoves(): Moves
    {
        return Action::all()->filterWith(function (Action $action) {
            return $this->maze->isValidPosition($this->hero->after($action));
        });
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        $new = clone $this;
        foreach ($moves as $move) {
            assert($move instanceof Action);
            $new->hero = $new->hero->after($move);
            $new->actionsSoFar = $new->actionsSoFar->add($move);
        }
        return $new;
    }

    public function hero(): Hero
    {
        return $this->hero;
    }
}
