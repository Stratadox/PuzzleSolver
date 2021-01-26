<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Sudoku;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Moves;
use function array_map;
use function sprintf;

final class Entry implements Move
{
    /** @var int */
    private $number;
    /** @var int */
    private $row;
    /** @var int */
    private $column;

    public function __construct(int $number, int $row, int $column)
    {
        $this->number = $number;
        $this->row = $row;
        $this->column = $column;
    }

    public static function allPossibilitiesFor(int $row, int $column): Moves
    {
        return new Moves(...array_map(
            static function (int $number) use ($row, $column): Entry {
                return new self($number, $row, $column);
            },
            range(1, 9)
        ));
    }

    public function number(): int
    {
        return $this->number;
    }

    public function column(): int
    {
        return $this->column;
    }

    public function row(): int
    {
        return $this->row;
    }

    public function __toString(): string
    {
        return sprintf(
            '%d at (%d, %d)',
            $this->number,
            $this->column,
            $this->row
        );
    }
}
