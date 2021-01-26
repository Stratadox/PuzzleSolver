<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Maze;

use function array_map;
use function implode;

final class Maze
{
    /** @var Goal */
    private $exit;
    /** @var Wall[] */
    private $walls;

    public function __construct(Goal $exit, Wall ...$walls)
    {
        $this->exit = $exit;
        $this->walls = $walls;
    }

    public static function withGoalAndWalls(Goal $exit, Wall ...$walls): self
    {
        return new self($exit, ...$walls);
    }

    public function isValidPosition(Hero $hero): bool
    {
        foreach ($this->walls as $wall) {
            if ($wall->blocks($hero->x(), $hero->y())) {
                return false;
            }
        }
        return true;
    }

    public function isCompletedBy(Hero $hero): bool
    {
        return $this->exit->isAt($hero->x(), $hero->y());
    }

    public function __toString(): string
    {
        $maze = [];
        $maxX = 0;
        $maxY = 0;
        foreach ($this->walls as $wall) {
            if ($wall->x() > $maxX) {
                $maxX = $wall->x();
            }
            if ($wall->y() > $maxY) {
                $maxY = $wall->y();
            }
        }
        for ($x = 0; $x < $maxX; ++$x) {
            for ($y = 0; $y < $maxY; ++$y) {
                $maze[$y][$x] = ' ';
            }
        }
        foreach ($this->walls as $wall) {
            $maze[$wall->y()][$wall->x()] = '#';
        }
        $maze[$this->exit->y()][$this->exit->x()] = 'X';
        return implode("\n", array_map('implode', $maze));
    }
}
