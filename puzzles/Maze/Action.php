<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Maze;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Moves;

final class Action implements Move
{
    private const LEFT = 'left';
    private const RIGHT = 'right';
    private const UP = 'up';
    private const DOWN = 'down';

    /** @var string */
    private $direction;

    private function __construct(string $direction)
    {
        $this->direction = $direction;
    }

    public static function fromString(string $direction): self
    {
        return new self($direction);
    }

    public static function all(): Moves
    {
        return new Moves(
            self::fromString(self::LEFT),
            self::fromString(self::RIGHT),
            self::fromString(self::UP),
            self::fromString(self::DOWN)
        );
    }

    public function newX(int $prevX): int
    {
        switch ($this->direction) {
            case self::RIGHT: return $prevX + 1;
            case self::LEFT: return $prevX - 1;
            default: return $prevX;
        }
    }

    public function newY(int $prevY): int
    {
        switch ($this->direction) {
            case self::UP: return $prevY - 1;
            case self::DOWN: return $prevY + 1;
            default: return $prevY;
        }
    }

    public function __toString(): string
    {
        return 'move ' . $this->direction;
    }
}
