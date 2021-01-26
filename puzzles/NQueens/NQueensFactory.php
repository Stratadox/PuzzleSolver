<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NQueens;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;

final class NQueensFactory implements PuzzleFactory
{
    public static function make(): PuzzleFactory
    {
        return new self();
    }

    public function fromString(string $puzzle): Puzzle
    {
        return NQueensPuzzle::forQueens((int) $puzzle);
    }
}
