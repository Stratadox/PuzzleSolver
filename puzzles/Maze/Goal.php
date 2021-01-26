<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Maze;

final class Goal
{
    /** @var int */
    private $x;
    /** @var int */
    private $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function isAt(int $x, int $y): bool
    {
        return $x === $this->x && $y === $this->y;
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }

    public function __toString()
    {
        return "({$this->x},{$this->y})";
    }
}
