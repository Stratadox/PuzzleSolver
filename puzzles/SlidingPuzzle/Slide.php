<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle;

use Stratadox\PuzzleSolver\Move;

final class Slide implements Move
{
    private const UP = 'up';
    private const DOWN = 'down';
    private const LEFT = 'left';
    private const RIGHT = 'right';
    private const ADD_ROW = [
        Slide::UP => -1,
        Slide::DOWN => 1,
        Slide::LEFT => 0,
        Slide::RIGHT => 0,
    ];
    private const ADD_COL = [
        Slide::UP => 0,
        Slide::DOWN => 0,
        Slide::LEFT => -1,
        Slide::RIGHT => 1,
    ];
    /** @var int */
    private $piece;
    /** @var string */
    private $direction;
    /** @var int */
    private $row;
    /** @var int */
    private $col;

    private function __construct(
        int $piece,
        string $direction,
        int $row,
        int $col
    ) {
        $this->piece = $piece;
        $this->direction = $direction;
        $this->row = $row;
        $this->col = $col;
    }

    public static function up(array $pieces, int $row, int $col): self
    {
        return new self($pieces[$row][$col], Slide::UP, $row, $col);
    }

    public static function down(array $pieces, int $row, int $col): self
    {
        return new self($pieces[$row][$col], Slide::DOWN, $row, $col);
    }

    public static function left(array $pieces, int $row, int $col): self
    {
        return new self($pieces[$row][$col], Slide::LEFT, $row, $col);
    }

    public static function right(array $pieces, int $row, int $col): self
    {
        return new self($pieces[$row][$col], Slide::RIGHT, $row, $col);
    }

    public function applyTo(array $pieces): array
    {
        $otherR = $this->row + self::ADD_ROW[$this->direction];
        $otherC = $this->col + self::ADD_COL[$this->direction];
        $swap = $pieces[$otherR][$otherC];
        $pieces[$otherR][$otherC] = $pieces[$this->row][$this->col];
        $pieces[$this->row][$this->col] = $swap;
        return $pieces;
    }

    public function __toString(): string
    {
        return "Slide {$this->piece} {$this->direction}";
    }
}
