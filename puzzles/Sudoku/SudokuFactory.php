<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Sudoku;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;
use function assert;
use function count;
use function preg_split;
use function str_replace;

final class SudokuFactory implements PuzzleFactory
{
    public static function make(): PuzzleFactory
    {
        return new self();
    }

    public function fromString(string $puzzle): Puzzle
    {
        $puzzle = str_replace([' ', '|', '+', '-'], '', $puzzle);
        $numbers = [];
        foreach (preg_split("/(\n)|(\r)/", $puzzle) as $line) {
            if (empty($line)) {
                continue;
            }
            $row = [];
            for ($c = 0; $c < 9; $c++) {
                if ($line[$c] === '.') {
                    $row[] = null;
                } else {
                    $row[] = (int) $line[$c];
                }
            }
            $numbers[] = $row;
        }
        assert(count($numbers) === 9);
        return SudokuPuzzle::fromArrays(...$numbers);
    }
}
