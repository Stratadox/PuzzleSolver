<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NQueens;

use Stratadox\PuzzleSolver\Moves;
use function array_slice;
use function count;
use function end;
use function implode;
use function range;
use function str_repeat;
use const PHP_EOL;

final class Board
{
    /** @var int */
    private $size;

    private function __construct(int $size)
    {
        $this->size = $size;
    }

    public static function ofSize(int $n): self
    {
        return new self($n);
    }

    public function isSolvedWith(QueenPlacement ...$queens): bool
    {
        return count($queens) === $this->size;
    }

    public function possibleMovesAfter(QueenPlacement ...$queens): Moves
    {
        $row = empty($queens) ? 0 : (end($queens)->row() + 1);
        $moves = [];
        foreach (range(0, $this->size - 1) as $column) {
            if (!$this->isBlocked($row, $column, ...$queens)) {
                $moves[] = new QueenPlacement($row, $column);
            }
        }
        return new Moves(...$moves);
    }

    private function isBlocked(
        int $row,
        int $column,
        QueenPlacement ...$queens
    ): bool {
        foreach ($queens as $queen) {
            if (
                $queen->column() === $column ||
                $queen->row() === $row ||
                abs($queen->row() - $row) === abs($queen->column() - $column)
            ) {
                return true;
            }
        }
        return false;
    }

    public function __toString(): string
    {
        $board = '  ' . implode('  ', array_slice(range('a', 'z'), 0, $this->size)) . PHP_EOL;
        foreach (range($this->size, 0, -1) as $i) {
            $board .= $i . ' ' . str_repeat('   ', $this->size) . PHP_EOL;
        }
        return $board;
    }
}
