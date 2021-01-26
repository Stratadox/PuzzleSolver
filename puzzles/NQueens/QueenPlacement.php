<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NQueens;

use Stratadox\PuzzleSolver\Move;

final class QueenPlacement implements Move
{
    /** @var int */
    private $row;
    /** @var int */
    private $column;

    public function __construct(int $row, int $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    public function row(): int
    {
        return $this->row;
    }

    public function column(): int
    {
        return $this->column;
    }

    public function __toString(): string
    {
        return $this->column . ',' . $this->row;
    }
}
